<?php
use phpoffice\phpexcel\Classes\PHPExcel\IOFactory;



	Class faceliftControllerCont extends BaseController {
	private $report = ['success'=>[], 'error'=>[]];

	private $rightWay = ['day',
				'date',
				's/no',
				'name',
				'gender',
				'address',
				'religion',
				'marital status',
				'wedding anniversary',
				'month & date of birth',
				'occupation',
				'phone no',
				'email',
				'service',
				'arrival',
				'service start',
				'service end',
				'departure',
				'staff attendant',
				'fee paid',
				'rebooked date',
				'referred',
				'facelift knowledge',
				'loyalty points',
				'customer status',
				'section'
		];
	private $rightWay4Exp = [
				'day',
				'date',
				's/no',
				'description',
				'amount',
				'purchased by',
				'approval',
				'user unit'
	];

	public function getUploadIncome() {
		return Response::view('facelift.uploads.income_upload');
	}

	Public function postUploadIncome() {
		$theArray = [];

		if(Input::file('incomeCsv') == '') {
			return Redirect::back()->with('info',"Pls Upload a CSV file as the file input cannot be empty");
		} 
		elseif(Input::file('incomeCsv')->getClientOriginalExtension() != 'csv') {
			return Redirect::back()->with('info',"The File must be a csv file");
		}

		// $objE = new PHPExcel_IOFactory;
		$file = Input::file('incomeCsv');
		$name = $file->getClientOriginalName();
		$path = public_path().'/uploads/'.$name;

		// $path = 'uploads/';
		if(file_exists($path)) {
			print("File exists in path<br />");
			print("Deleting and Replacing file<br />");

			unlink($path);
		} 

		if($file->move('uploads',$name)) {
				print("File moved successfully<br />");
		} else {
				print("File move Unsuccessful<br />");
		}
		$c = 0;
		$fileRead = PHPExcel_IOFactory::load($path);
		foreach($fileRead->getWorksheetIterator() as $wksht) {
			foreach ($wksht->getRowIterator() as $row) {
				foreach($row->getCellIterator() as $cell) {
						$theArray[$c][] = $cell->getCalculatedValue(true);
				}
				$c++;
			}
			
		}

		$titles = array_shift($theArray);

		$this->checkHd($titles,$this->rightWay);
		if(isset($this->report['error']['heading error'])) {
			return Redirect::back()
					->with('info',"Please Correct the following errors")
					->with('error',$this->report['error']['heading error']);
		}

		$combinesArray = [];
		foreach ($theArray as $array) {
			if(!empty($array)) {
				$combinedArray = count($this->rightWay) == count($array) ? array_combine($this->rightWay, $array) : [];
			}

			if(!empty($combinedArray)) {
				$customer = DB::select('select * from faceliftcustomers where client_name like \'%'.$combinedArray['name'].'%\'');
				$combinedArray['date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime(str_replace('/','-',$combinedArray['date'].' '.$combinedArray['service start'])));
				$combinedArray['rebooked date'] = str_replace('/','-',$combinedArray['rebooked date']);
				$combinedArray['wedding anniversary'] = str_replace('/','-',$combinedArray['wedding anniversary']);
				$combinedArray['month & date of birth'] = str_replace('/','-',$combinedArray['month & date of birth']);
				// return dd($combinedArray);
				
				if(!empty($customer) && isset($customer[0])) {
					// then add to services
					$combinedArray['client_id'] = $customer[0]->client_id;
					

					$serviceModel = new FaceliftServices;
					$chckService = FaceliftServices::where('client_id','=',$combinedArray['client_id'])
													->where('service_type','=',$combinedArray['service'])
													->where('service_start','=',strftime("%H:%M:%S",strtotime($combinedArray['service start'])))
													->where('service_end','=',strftime("%H:%M:%S",strtotime($combinedArray['service end'])))
													->where('created_at','=',$combinedArray['date'])
													->get()->toArray();
					// return dd($chckService);
					if(empty($chckService)) {
							if($serviceModel->addServices($combinedArray)) {
								$this->report['success']['services'][] = 'Service added for '.ucwords($combinedArray['name']).' successfull';
							} else {
								$this->report['error']['services'][] = 'Service added for '.ucwords($combinedArray['name']).' not successfull';

							}

					} else {
						$this->report['error'][] = 'Service added for '.ucwords($combinedArray['name']).' exists in the database';
					}
					

				} else {
					// add customer first and then add services
					$customerModel = new FaceliftCustomers;
					$client_id = '';

					if(!empty($combineArray['name'])) {
						if($customerModel->addCustomers($combinedArray)) {
							$customer = FaceliftCustomers::where('client_name','=',$combinedArray['name'])->get();
							$combinedArray['client_id'] = $customer[0]->client_id;

							$serviceModel = new FaceliftServices;

							if($serviceModel->addServices($combinedArray)) {
								$this->report['success']['services'][] = 'Service added for '.ucwords($combinedArray['name']).' successfull';
							} else {
								$this->report['error']['services'][] = 'Service added for '.ucwords($combinedArray['name']).' not successfull';

							}
						} else {
							$this->report['error'][] = "Customer with the name ".$combinedArray['name'].' was not saved to the database';
							// exit;
						}
					} else {
						$this->report['error'][] = "Customer at line ". $combinedArray['s/no'] .' has no name';
					}

				}
			}
				
		}



		




		return Redirect::back()->with('error',$this->report['error'])->with('success',$this->report['success']);
			
	}

	private function checkHd($array = [], $rightWay) {
		if(count($array) != count($rightWay)) {
			$this->report['error']['heading error'][] = "The columns are not ".count($rightWay).' in number';
		}
		
		for ($i=0; $i < count($rightWay) ; $i++) { 
			if(trim(strtolower($array[$i])) != trim(strtolower($rightWay[$i]))) {
				$this->report['error']['heading error'][] = "Column No. ". ($i+1) ." should be ".$rightWay[$i];
			}
		}
	}

	

	

	public function getUploadExpenses() {
		return Response::view('facelift.uploads.expenses_upload');
	}
	

	public function postUploadExpenses() {
		$theArray = [];

		if(Input::file('incomeCsv') == '') {
			return Redirect::back()->with('info',"Pls Upload a CSV file as the file input cannot be empty");
		} 
		elseif(Input::file('incomeCsv')->getClientOriginalExtension() != 'csv') {
			return Redirect::back()->with('info',"The File must be a csv file");
		}

		// $objE = new PHPExcel_IOFactory;
		$file = Input::file('incomeCsv');
		$name = $file->getClientOriginalName();
		$path = public_path().'/uploads/'.$name;

		// $path = 'uploads/';
		if(file_exists($path)) {
			print("File exists in path<br />");
			print("Deleting and Replacing file<br />");

			unlink($path);
		} 

		if($file->move('uploads',$name)) {
				print("File moved successfully<br />");
		} else {
				print("File move Unsuccessful<br />");
		}
		$c = 0;
		$fileRead = PHPExcel_IOFactory::load($path);
		foreach($fileRead->getWorksheetIterator() as $wksht) {
			foreach ($wksht->getRowIterator() as $row) {
				foreach($row->getCellIterator() as $cell) {
						$theArray[$c][] = $cell->getCalculatedValue();
				}
				$c++;
			}
			
		}

		$titles = array_shift($theArray);

		$this->checkHd($titles,$this->rightWay4Exp);
		if(isset($this->report['error']['heading error'])) {
			return Redirect::back()
					->with('info',"Please Correct the following errors")
					->with('error',$this->report['error']['heading error']);
		}

		$combinedArray = [];

		foreach ($theArray as $array) {
			if(!empty($array)) {
				$combinedArray = count($this->rightWay4Exp) == count($array) ? array_combine($this->rightWay4Exp, $array) : [];
			}

			if(!empty($combinedArray)) {
				$combinedArray['date'] = strftime('%Y-%m-%d',strtotime(str_replace('/','-',$combinedArray['date'])));

				$combinedArray['created_at'] = strftime('%Y-%m-%d',strtotime(str_replace('/','-',$combinedArray['date']))).' '.strftime('%H:%M:%S', strtotime('now'));

				$model = new FaceliftExpenseControl;
				$chckExpense = $model->where('created_at','=',$combinedArray['created_at'])->where('approval','=',$combinedArray['approval'])->where('staff','=',$combinedArray['purchased by'])->get();
				$cat = FaceliftBudgetCategories::where('category','like',$combinedArray['description'])->get();
				$combinedArray['cat_id'] = $cat[0]->category_id;

				if(empty($chckExpense) && !empty($cat)) {
					$re = $model->addExpenseFromFile($combinedArray);
						if( $re ) {
							$this->report['success'][] = 'Expense at row '.$combinedArray['s/no'].' added succesfully';
						} else {
							$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' failed to add to database';
						}
				} elseif(!empty($chckExpense) && !empty($cat)) {
					$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' already saved database';
				} else {
					$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' failed to add to database - unknown error';
				}
				// $this->report['success'][] = $model->addExpenseFromFile($combinedArray);
				
			} else {
				$this->report['error'][] = 'Expense at row empty';
			}

			
		}

		return Redirect::back()->with('error',$this->report['error'])->with('success',$this->report['success']);

		// echo "<pre>";
		// 		print_r($combinedArray);
		// 		echo "</pre>";
	}

	}