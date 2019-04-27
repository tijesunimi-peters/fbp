<?php
use phpoffice\phpexcel\Classes\PHPExcel\IOFactory;

Class faceliftController extends BaseController {
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
				'type',
				'description',
				'amount',
				'purchased by',
				'approval',
				'user unit'
	];

	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		// $this->beforeFilter('auth.mine');
		//$this->beforeFilter('adminCheck');
		if(Auth::check()) {
			Session::set('user', Auth::user()->username);
		} else {
			return Redirect::to('facelift/user-login')->with('bad-report','You are Logged Out');
		}
	}
	Public function getUserLogout() {
		Auth::logout();
		//FaceliftCustomers::find(6)->services()->delete();
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
	}
	//this Returning page for a new service
	public function getIndex() {
		if(Auth::check()) {
			$totalservices = count(FaceliftServices::all());
		$totalcustomers = count(FaceliftCustomers::all());
		$lastService = FaceliftServices::orderBy('created_at','DESC')->first();
		$service = FaceliftServices::all();
		$customers = FaceliftCustomers::orderBy('DOB','ASC')->get();
		$customersByWA = FaceliftCustomers::orderBy('wedding_anniversary','ASC')->get();
		$customersByRebook = FaceliftServices::orderBy('rebooked_date','ASC')->get();
		$budget = FaceliftBudget::where('month','=',strftime('%m',strtotime('today')))->where('year','=',strftime('%Y',strtotime('today')))->first();
		//return dd($budget);
		$exp = FaceliftExpenseControl::where('month','=',strftime('%m',strtotime('today')))->where('year','=',strftime('%Y',strtotime('today')))->get();
		$expArray = array();
		if(count($exp) > 0 && count($budget)>0) {
		foreach($exp as $expense) {
			$expArray[] = $expense->amount;
		}
		$expTotal = array_sum($expArray);
		
		}
		
		return View::make('facelift.usersProfile')->with('services',$totalservices)
											->with('customers',$totalcustomers)
											->with('lastService',$lastService)
											->with('serviceAll',$service)
											->with('allCustomers',$customers)
											->with('WA',$customersByWA)
											->with('RD',$customersByRebook)
											->with('bud',$budget)
											->with('cum_fig',$expArray);
		} else {
			return Redirect::to('facelift/user-login');
		}
	}
	public function getNewService() {
		
		if(Auth::check()) {
		return View::make('facelift.returningCustomers');
		} else {
			return Redirect::to('facelift/user-login');
		}
		//return View::make('facelift.returningCustomers');
	}
	public function getUserRegistration() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1){
		
				return View::make('facelift.userRegister');
		} else {
			return Redirect::back()->with('info','You dont have access to view this page');
		}
	}
	public function postUserRegistration() {
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$rules = [
				'username'=>'required|unique:faceliftusers,username',
				'password'=>'required|same:repeat_password'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::to('facelift/user-registration')->withErrors($v)->withInput();
		}
		$user = new FaceliftUsers();
		$user->username = Input::get('username');
		$user->password =Hash::make(Input::get('password'));
		$user->admin = Input::get('admin') ? 1 : 0;
		$user->super_admin = Input::get('super_admin') ? 1 : 0;
		//return dd($user);
		if($user->save()) {
			return Redirect::back()->with('report','User Added  Successfully');
		}
			
		} else {
			return Redirect::back()->with('info','You dont have access to view this page');
		}
		
		//if()
		//return View::make('facelift.userRegister');
	}
	public function getUserLogin() {
		return View::make('facelift.userLogin');
	}
	Public function postUserLogin() {
		$rules = [
			'username'=>'required',
			'password'=>'required'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::to('facelift/user-login')->withErrors($v)->with('bad-report','Login Failed; Please Try Again')->withInput();
		}
		$faceliftuser = array(
					'username' => Input::get('username'),
					'password' => Input::get('password')
				);
		if(Auth::attempt($faceliftuser)) {
			return Redirect::to('facelift/index');
		} else {
			return Redirect::to('facelift/user-login')->with('bad-report', "Username and Password combination incorrect; Please Try Again");
		}
	}
	/*
	Add New Service: adds services and checks for Referrals
	if there is saves and calculates commission if the referral is Staff
	or calculates the Loyalty points if the Referral is a Customer
	*/
	public function postAddNewService() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		
		$customer = FaceliftCustomers::find(Input::get('client_id'));
		$referee = '';
		$checkReferral = '';
		//Rules for checking the fields of the input
		$rules = [
			'date' => 'required',
			'service' => 'required',
			'service-start' => 'required',
			'service-end' => 'required',
			'attendant' => 'required',
			'amount' => 'required',
			'arrival' => 'required',
			'departure' => 'required',
			'rebooked-date' => 'required'
		];
		//Validate the fields and redirect if it fails
		$validator = Validator::make( Input::all(), $rules);
		if($validator->fails()) {
			return Redirect::to('facelift/service/'.$customer->client_name)->withErrors($validator)->withInput();
		}
		//Checks if the attendant is a true staff
		$attendant = FaceliftStaff::where('name','=',Input::get('attendant'))->first();
		if(!$attendant) {
			return Redirect::to('facelift/service/'.$customer->client_name)->with('bad-report','The Attendant provided is not a Staff of Facelift')->withInput();
		}
		$serviceArray = explode(', ',Input::get('service'));
		if(in_array('',$serviceArray)) {
			array_pop($serviceArray);
		}
		$amountArray = explode(',',Input::get('amount'));
		if(in_array('',$amountArray))
		{
			array_pop($amountArray);
		}
		if(count($serviceArray) != count($amountArray)) {
			return Redirect::to('facelift/service/'.$customer->client_name)->with('bad-report','the Number of Amount and Services Rendered does not Correspond. Please Check and Try again')->withInput();
		}
		//$service_amount = ;
		$service_amount = array_combine($serviceArray,$amountArray);
		$serviceOutput = array();

		foreach($service_amount as $key => $value) {
			$serviceOutput[] = $key.' ('.$value.')';
		}
		//Instantiating a new service for saving
		$service = new FaceliftServices();
		$service->service_type = implode('; ',$serviceOutput); //Implode('; ',$serviceArray);
		$service->service_start = strftime('%H:%M:%S',strtotime(Input::get('service-start')));
		$service->service_end = strftime('%H:%M:%S',strtotime(Input::get('service-end')));
		$service->attendant = Input::get('attendant');
		$service->amount = array_sum($amountArray);
		$service->month = strftime('%m',strtotime('today'));
		$service->year = strftime('%Y',strtotime('today'));
		$service->arrival = strftime('%H:%M:%S',strtotime(Input::get('arrival')));
		$service->departure = strftime('%H:%M:%S',strtotime(Input::get('departure')));
		$service->rebooked_date = Input::get('rebooked-date');
				$service = $service->customer()->associate($customer);		//associate the customer to the service;
		//$service = $service->staff()->associate()
		
		$staff = DB::table('faceliftstaff')->where('name','=',Input::get('attendant'))->first();
		if(count($staff) == 0) {
			return Redirect::back()->with('info','attendant is not found')->withInput();
		}
		//return dd($staff);
		$staff_id = FaceliftStaff::find($staff->staff_id)->first();
		if(strtotime($staff_id->deleted_at) > strtotime('NULL')) {
			return Redirect::back()->with('bad-report','The Staff attendant is not active');
		}
		$service = $service->staff()->associate($staff_id);
		
		//find the customer to get the initial referee
		
		
		if($customer) {
			if(DB::table('faceliftstaff')->where('name','=',$customer->referee)) {
			$referee = DB::table('faceliftstaff')->where('name','=',$customer->referee)->first();
			if($referee) {
			$checkReferral = DB::table('faceliftreferrals')->where('staff_id','=',$referee->staff_id)->where('refered','=',$customer->client_name)->first();
			}
		}
		}
		//instantiating a new FaceliftStaff Commission and filling out all the columns
		if($referee) {
			//return dd($checkReferral);
			if(!empty($checkReferral->staff_id) && !empty($checkReferral->refered)) {
		$staffCommission = new FaceliftStaffCommission();
		$totalCommissionArray = array();
		foreach($amountArray as $amounteach) {
			$totalCommissionArray[] = 0.05 * $amounteach;
		}
		$staffCommission->services = implode('; ',$serviceOutput);
		$staffCommission->month = strftime('%m',strtotime('today'));
		$staffCommission->year = strftime('%Y',strtotime('today'));
		$staffCommission->commission = 0.05;
		$staffCommission->amount = array_sum($amountArray);
		$staffCommission->total = array_sum($totalCommissionArray); //0.05 * intval(Input::get('amount'));
		$staffCommission->customer_id = $customer->client_id; //gets the customer Id
		$realStaff = FaceliftStaff::find($referee->staff_id); //find the staff to get the staff id
		$staffCommission = $staffCommission->staff()->associate($realStaff); // associate the staff to the commission
		
				if($service->save()) {
					if($staffCommission->save()) {
						return Redirect::to('facelift/service/'.$customer->client_name)->with('report','Service has been Saved with Referral');
					}
				}
			} else {
					return Redirect::back()->with('bad-report','Something went wrong; Please Try Again; Customer has Refree')->withInput();
				}
		} else {
		if($service->save()) {
		return Redirect::to('facelift/service/'.$customer->client_name)->with('report','Service has been Saved with No Referrals');
		} else {
					return Redirect::back()->with('bad-report','Something went wrong; Please Try Again; No Referee')->withInput();
		}
		}
	}
	//Lists all the customers
	public function getCustomers() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$customers = DB::table('faceliftcustomers')->paginate(10);
		return View::make('facelift.customers')->with('customers',$customers);
	}
	//Gets a customer service history
	public function getCustomerHistory($client_id)
	{
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$customerHistory = DB::table('faceliftservices')->orderBy('created_at','DESC')->where('client_id','=',$client_id)->get();
		$customer = DB::table('faceliftcustomers')->where('client_id','=',$client_id)->first();
		return View::make('facelift.CustomerServiceHistory')->with('customerHistory',$customerHistory)->with('customer',$customer);
	}
	//Helps to add New Customer
	public function getAddCustomer() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		return View::make('facelift.index');
	}
	public function postAddCustomer() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$rules = [
			'client_id'=>'required|unique:faceliftcustomers,client_id',
			'client_type'=>'required',
			'name'=>'required|unique:faceliftcustomers,client_name',
			'sex'=>'required',
			'houseNo'=>'',
			'street'=>'',
			'location'=>'',
			'city'=>'',
			'religion'=>'required',
			'status'=>'required',
			'anniversary'=>'required_if:status,married',
			'DOB'=>'required',
			'occupation'=>'',
			'phone_no'=>'required|digits:11',
			'email'=>'required|email',
			'referee'=>'required_if:client_type,referral'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::to('facelift/add-customer')->withErrors($v)->withInput();
		}
		// Getting all the fields field for the new customer
		$customer = new FaceliftCustomers();
		$customer->client_id = Input::get('client_id');;
		$customer->client_type = Input::get('client_type');
		$customer->client_name = strtolower(Input::get('name'));
		$customer->gender = Input::get('sex');
		$customer->houseNo = Input::get('houseNo');
		$customer->street = Input::get('street');
		$customer->location = Input::get('location');
		$customer->city = Input::get('city');
		$customer->religion = Input::get('religion');
		$customer->status = Input::get('status');
		$customer->wedding_anniversary = Input::get('anniversary');
		$customer->DOB = Input::get('DOB');
		$customer->occupation = Input::get('occupation');
		$customer->phone_no = Input::get('phone_no');
		$customer->email = Input::get('email');
		if(Input::get('client_type') == 'referral') {
		$customer->referee = strtolower(Input::get('referee'));
		} else {
			$customer->referee = '';
		}
		$customer->flk = Input::get('flk');
		$customer->month = strftime('%m',strtotime('today'));
		$customer->year = strftime('%Y',strtotime('today'));
		// if there is referral then do below
			
			
			if(Input::get('client_type') == 'referral') {
				$ref_id = '';
				$referral = new FaceliftReferrals();
			$referral->client_id = Input::get('client_id');
			$referral->refered = Input::get('name');
			$referral->month = strftime('%m',strtotime('today'));
			$referral->year = strftime('%Y',strtotime('today'));
			
			
			$staffReferee = DB::table('faceliftstaff')->where('name','=',Input::get('referee'))->first();
			$customerReferee = DB::table('faceliftcustomers')->where('client_name','=',Input::get('referee'))->first();
			
			if(!empty($staffReferee)) {
			$ref_id = FaceliftStaff::find($staffReferee->staff_id);
			$referral->staff()->associate($ref_id);
			} elseif(!empty($customerReferee)) {
				$ref_id = FaceliftCustomers::find($customerReferee->client_id);
				$referral->customer()->associate($ref_id);
			} else {
				return Redirect::to('facelift/add-customer')->with('error','The Referee is not registered; Please check to make sure the Referee Name is Correct<br /> OR <br />Choose Client Type as Walk In Instead')->withInput();
			
			}
				if($customer->save()){
					$referral->save();
						return Redirect::to('facelift/customers')->with('report', 'The Customer and his/her Referee has been registered')
						->with('customer_id',Input::get('client_id')); //then give a link to view the customer
					
				}
		} else {
			if($customer->save()) {
				return Redirect::to('facelift/customers')->with('report', 'The Customer has been registered')
						->with('customer_id',Input::get('client_id'));
			}
		}
		
	
	}
	//Returns to page to add new Service to a particular Customer
	public function getService($client_name) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$customer = DB::table('faceliftcustomers')->where('client_name','=',$client_name)->first();
		$lp = FaceliftCustomers::find($customer->client_id)->loyalty_points()->get();
				//return dd($lp);
		$serviceHistory = DB::table('faceliftservices')->orderBy('created_at','DESC')->where('client_id','=',$customer->client_id)->get();
		return View::make('facelift.service')->with('customer',$customer)->with('serviceHistory',$serviceHistory)->with('lp',$lp);
	}
//finds the client's Id and then redirects to Customer Service Page
	public function postService() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$rules = [
			'client_name'=>'required|exists:faceliftcustomers,client_name'
		];
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()) {
			return Redirect::to('facelift/new-service')->withErrors($validation)->withInput();
		}
		return Redirect::to('facelift/service/'.Input::get('client_name'));
		
	}
//Viewing Details for Each Customer
	Public function getViewCustomer($customer_id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$customer = DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->first();
		//return dd($customer[0]->client_id);
		return  View::make('facelift.EachCustomerView')->with('customer',$customer);
	}
//Editing Customer Information
	Public function getEditCustomer($customer_id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$customer = DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->first();
			return View::make('facelift.EachCustomerEdit')->with('customer',$customer);
		} else {
			return 'Only Admin is allowed to Edit Customer info';
			//return dd(Auth::user()->admin);
		}
	}
	public function postEditCustomer($customer_id) {
		$rules = [
			'client_name' => 'required',
			'gender' => 'required',
			'houseNo' => 'required',
			'street' => 'required',
			'location' => 'required',
			'city' => 'required',
			'religion' => 'required',
			'status' => 'required',
			'wedding_anniversary' => 'required_if:status,married',
			'DOB' => 'required',
			'occupation' => 'required',
			'phone_no' => 'required',
			'email' => 'required'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return View::make('facelift.ajaxTemplate')->withErrors($v);
		}
		$checkReferrals = '';
		$referralName = array();
		$referedCustomer = array();
		$updateValues = [
			'client_name'=>strtolower (Input::get('client_name')),
			'gender'=>Input::get('gender'),
			'houseNo'=>Input::get('houseNo'),
			'street'=>Input::get('street'),
			'location'=>Input::get('location'),
			'city'=>Input::get('city'),
			'religion'=>Input::get('religion'),
			'status'=>Input::get('status'),
			'wedding_anniversary'=>Input::get('wedding_anniversary'),
			'DOB'=>Input::get('DOB'),
			'occupation'=>Input::get('occupation'),
			'phone_no'=>Input::get('phone_no'),
			'email'=>Input::get('email')
		];
		//$customerReferee = DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->first();
		//return dd($customerReferee);
		$checkReferrals = FaceliftCustomers::find($customer_id)->referrals;
		if($checkReferrals) {
			foreach ($checkReferrals as $key) {
				$referralName[] = $key->client_id;
			}
			if($referralName) {
				foreach($referralName as $key) {
					$referedCustomer[] = DB::table('faceliftcustomers')->select('client_id')->where('client_id','=',$key)->first();
				}
				}
			//return dd($referedCustomer);
			if(DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->update($updateValues)) {
			foreach($referedCustomer as $key) {
				DB::table('faceliftcustomers')->where('client_id','=',$key->client_id)->update(['referee'=>strtolower(Input::get('client_name'))]);
			}
			$customerReferral = DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->first();
			if($customerReferral) {
				DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->update(['refered'=>strtolower(Input::get('client_name'))]);
			}
			Session::flash('report', 'Customer Update Successful with Referrals');
			return '<div class="well well-small">Customer Edit Successful. <br />Reloading.......</div>';
			} else {
				return Session::flash('report', 'Nothing to Update');
			}
		} else {
		
		if(DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->update($updateValues)) {
			$customerReferral = DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->first();
			if($customerReferral) {
				DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->update(['refered'=>strtolower(Input::get('client_name'))]);
			}
			
			Session::flash('report', 'Customer Update Successful');
			return '<div class="well well-small">Customer Edit Successful. <br />Reloading.......</div>';
		}
		}
	}
	public function getDeleteCustomer($customer_id) {
			if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
			}
			if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
				$customerServices = FaceliftCustomers::find($customer_id)->services()->get();
			if($customerServices) {
				FaceliftCustomers::find($customer_id)->services()->delete();
				//return dd($customerServices);
			}
				if(DB::table('faceliftcustomers')->where('client_id','=',$customer_id)->delete()) {
				$customerReferral = DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->first();
			if($customerReferral) {
				DB::table('faceliftreferrals')->where('client_id','=',$customer_id)->delete();
					}
			
					Session::flash('report','Customer Delete Successful');
					return 'Reloading......';
				}
			} else {
				return 'Only Admin is allowed Here';
			}
	}
//Getting all the Referrals
	Public function getAllReferrals($filter = NULL) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if($filter == 'staff') {
			$referrals = DB::table('faceliftreferrals')->where('staff_id','!=','')->paginate(10);
			return View::make('facelift.allReferrals')->with('allReferrals',$referrals);
		} elseif($filter == 'clients') {
			$referrals = DB::table('faceliftreferrals')->where('customer_id','!=','')->paginate(10);
			return View::make('facelift.allReferrals')->with('allReferrals',$referrals);
		} else {
		$referrals = DB::table('faceliftreferrals')->orderBy('created_at','DESC')->paginate('10');
		return View::make('facelift/allReferrals')->with('allReferrals', $referrals);
	}
	}
//Getting all the services
	Public function getAllServices() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(isset($_GET['month']) && isset($_GET['year'])) {
			$month = $_GET['month'];
			$year = $_GET['year'];
			$filter = array();
			$services = DB::table('faceliftservices')->where('month','=',$month)->where('year','=',$year)->orderBY('created_at','DESC')->paginate('10');
			
			if($services && (count($services) > 0)) {
			return View::make('facelift/allservices')->with('allServices',$services);
			
			} else {
				return Redirect::back()->with('info','There is no record of services for the selected month');
			}
		} else {
		$services = DB::table('faceliftservices')->orderBY('created_at','DESC')->paginate('10');
		return View::make('facelift/allservices')->with('allServices',$services);
			}
	}
//Deleting Services
	Public function postServiceDelete($serviceId) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$service = DB::table('faceliftservices')->where('id','=',$serviceId)->first();
		return View::make('facelift/serviceDelete')->with('report','Only the Admin can Do this')->with('service',$service);
	}
//Getting the Staff Commissions
Public function getStaffCommissions() {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		$commissionList = DB::table('faceliftstaffcommission')->paginate(50); //FaceliftStaffCommission::all();
		return View::make('facelift/staffCommission')->with('commissions',$commissionList);
	} else {
		return Redirect::back()->with('info','You dont have access to view this page');
	}
}
//Getting All the Staff
Public function getAllStaff() {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		$staffList = DB::table('faceliftstaff')->paginate(50);
		return View::make('facelift.staff')->with('staffList',$staffList);
	} else {
		return Redirect::back()->with('info','You dont have access to view this page');
	}
}
//New Staff
Public function getNewStaff() {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			return View::make('facelift.newStaff');
	}else{
		return Redirect::back()->with('info','You dont have access to view this page');
	}
}
	
	Public function postNewStaff() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$rules = [
				'staff_id' => 'required|unique:faceliftstaff,staff_id',
				'name' => 'required|unique:faceliftstaff,name',
				'address' => 'required',
				'phone_no' => 'required|digits:11',
				'email' => 'email',
				];
			$v = Validator::make(Input::all(), $rules);
			if($v->fails()) {
				return Redirect::to('facelift/new-staff')->withErrors($v)->withInput();
			}
			$staffIdArray = [
				'staff_id' => Input::get('staff_id')
			];
			$rules2 = [
				'staff_id'=>'unique:faceliftstaff,staff_id'
			];
			$vStaffId = Validator::make($staffIdArray, $rules2);
			if($vStaffId->fails()) {
				return Redirect::to('facelift/new-staff')->with('info','The Generated staff id already exists; Please Try again');
			}
			$staff = new FaceliftStaff();
			$staff->staff_id = Input::get('staff_id');
			$staff->name = strtolower(Input::get('name'));
			$staff->address = Input::get('address');
			$staff->phone_no = Input::get('phone_no');
			$staff->email = Input::get('email');
			$staff->month = strftime('%m',strtotime('today'));
			$staff->year = strftime('%Y',strtotime('today'));
			if($staff->save()) {
				return Redirect::to('facelift/new-staff')->with('info','Staff add Successfully');
			}
		} else {
			return Redirect::back()->with('info','You dont have access to view this page');
		}
	}
	
//getting all users
	Public function getAllUsers() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		$allUsers = DB::table('faceliftusers')->paginate(50);
		return View::make('facelift.allUsers')->with('users',$allUsers);
	} else {
		//return 'You dont have access to view this page';
		return Redirect::back()->with('info','You dont have access to view this page');
	}
		}
//Profile for the users
	Public function getUsersProfile() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$totalservices = count(FaceliftServices::all());
		$totalcustomers = count(FaceliftCustomers::all());
		$lastService = FaceliftServices::orderBy('created_at','DESC')->first();
		$service = FaceliftServices::all();
		$customers = FaceliftCustomers::orderBy('DOB','ASC')->get();
		$customersByWA = FaceliftCustomers::orderBy('wedding_anniversary','ASC')->get();
		$customersByRebook = FaceliftServices::orderBy('rebooked_date','ASC')->get();
		$budget = FaceliftBudget::where('month','=',strftime('%m',strtotime('today')))->where('year','=',strftime('%Y',strtotime('today')))->first();
		//return dd($budget);
		$exp = FaceliftControl::where('month','=',strftime('%m',strtotime('today')))->where('year','=',strftime('%Y',strtotime('today')));
		$expArray = array();
		foreach($exp as $expense) {
			$expArray[] = $expense->amount;
		}
		$expTotal = array_sum($expArray);
		$budgetBal = $budget->budget - $expTotal;
		return View::make('facelift.usersProfile')->with('services',$totalservices)
											->with('customers',$totalcustomers)
											->with('lastService',$lastService)
											->with('serviceAll',$service)
											->with('allCustomers',$customers)
											->with('WA',$customersByWA)
											->with('RD',$customersByRebook)
											->with('bud',$budget)
											->with('cum_fig',$expArray);
											//->with('bud_bal',$budgetBal);
	}
//Expense Controll
	Public function getExpensesControl() {
		if(!Auth::check())
		{
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		return View::make('facelift.expenseControlForm');
	} else {
		return Redirect::back()->with('info','You dont have access to view this page');
	}
	}
	Public function postExpensesControl() {
		if(!Auth::check())
		{
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			
		$rules = [
			'type' => 'required',
			'particulars' => 'required',
			'amount' => 'required',
			'staff' => 'required',
			'approval' => 'required',
			'user-unit' => 'required'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::back()->withErrors($v)->withInput();
		}
		$expense = new FaceliftExpenseControl();
		$expense->month = strftime('%m',strtotime('today'));
		$expense->year = strftime('%Y',strtotime('today'));
		$expense->type = Input::get('type');
		$expense->category_id = Input::get('type');
		$expense->particulars = Input::get('particulars');
		$expense->amount = Input::get('amount');
		$expense->approval = Input::get('approval');
		$expense->staff = Input::get('staff');
		$expense->user_unit = Input::get('user-unit');
		if($expense->save()) {
			return Redirect::to('facelift/expenses-control')->with('report','Expense Saved');
		}
	} else {
		return Redirect::back()->with('info','You dont have access to view this page');
		}
	}
	public function getAllExpenses() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			if(isset($_GET['month']) && isset($_GET['year'])){
			$month = Input::get('month');
				$year = Input::get('year');
				$reportArray = ['type'=>'search','month'=>$month,'year'=>$year];
			$expenses = DB::table('faceliftcontrol')->where('month','=',$_GET['month'])->where('year','=',$_GET['year'])->paginate(10);
			$budget = FaceliftBudget::where('month_year','=',$_GET['month'].'-'.$_GET['year'])->first();
			$date = $_GET['year'].'-'.$_GET['month'];
			$filter = [strftime('%B',strtotime($date))];
			$categories = FaceliftBudgetCategories::all();
			//return dd($expenses[0]);
			if($expenses && $budget) {
			return View::make('facelift.allExpenses')->with('report',$reportArray)->with('allExpenses',$expenses)->with('budget',$budget)->with('month',$filter)->with('categories',$categories);
		} else {
			return Redirect::back()->with('info','There is no record of expenses for the Selected Month');
		}
			} else {
				$month = strftime('%m',strtotime('today'));
				$year = strftime('%Y',strtotime('today'));
				$reportArray = ['type'=>'All','month'=>$month,'year'=>$year];
				$categories = FaceliftBudgetCategories::all();
		$expenses = DB::table('faceliftcontrol')->paginate(10);
		$budget = FaceliftBudget::where('month_year','=',strftime('%m-%Y',strtotime('today')))->first();
		$filter = ['All'];
		return View::make('facelift.allExpenses')->with('allExpenses',$expenses)->with('budget',$budget)->with('month',$filter)->with('categories',$categories)->with('report',$reportArray);
	}
	} else {
		return Redirect::back()->with('info', 'You dont have access to view this page');
	}
	}
	public function getNewBudget() {
		if(!Auth::check())
		{
			return Redirect::to('facelift/users-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			return View::make('facelift.newBudget');
		}
		else {
		return Redirect::back()->with('info', 'You dont have access to view this page');
		}
	}
	Public function postNewBudget() {
		if(!Auth::check())
		{
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$rules = ['month'=>'required',
						'year'=>'required',
						'category'=>'required',
						'month-year'=>'required',
						'budget'=>'required'];
			$msg = [ 'month-year.unique'=>'There is already a budget for this month'
			];
			$v = Validator::make(Input::all(), $rules,$msg);
			if($v->fails()) {
				return Redirect::back()->withErrors($v)->withInput();
			}
			$budget = new FaceliftBudget();
			$budget->month = Input::get('month');
			$budget->year = Input::get('year');
			$budget->category = Input::get('category');
			$budget->category_id = Input::get('category');
			$budget->month_year = Input::get('month-year');
			$budget->budget = Input::get('budget');
			if($budget->save()) {
				return Redirect::back()->with('report','Budget saved');
			}
		}
		else {
		return Redirect::back()->with('info', 'You dont have access to view this page');
		}
	}
//get the Report Page
Public function getReport() {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	return View::make('facelift.report');
}
Public function postReport($report = 'NULL') {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$rules = ['month'=>'','year'=>''];
				$v = Validator::make(Input::all(), $rules);
				if($v->fails()) {
					return Redirect::back()->withErrors($v)->withInput();
				}
				$clients = FaceliftCustomers::all();
				$staff = FaceliftStaff::all();
				$allServices = FaceliftServices::all();
				$referrals = DB::table('faceliftreferrals')->where('staff_id','!=','')->orderBy('created_at','DESC')->get();
				$clientReferrals = DB::table('faceliftreferrals')->where('client_id','!=','')->orderBy('created_at','DESC')->get();
				$month = Input::get('month');
				$year = Input::get('year');
				$frommonth = Input::get('frommonth');
				$fromyear = Input::get('fromyear');
				$toyear = Input::get('toyear');
				$tomonth = Input::get('tomonth');
				$type = Input::get('type');
				$all = Input::get('all') ? 1 : 0;
				$download = Input::get('download') ? 1 : 0;
				$reportArray = ['all'=>$all,'type'=>$type,'month'=>$month,'year'=>$year,'frommonth'=>Input::get('frommonth'),'fromyear'=>Input::get('fromyear'),'tomonth'=>Input::get('tomonth'),'toyear'=>Input::get('toyear')];
				/*****************Staff Commission***************************/
				if($frommonth > $tomonth && $fromyear >= $toyear && $all != 1) {
						return Redirect::back()->with('info','This Year and Month range Selection is Invalid; Please check and Try again')->withInput();
				}
			if($type == 'staff_commission') {
				
				if($all == 1) {
					//return 'This is the all section';
					$reportArray['cat'] = 'All Staff Commission Report';
					return View::make('facelift.report.reportTemplate')->with('staff',$staff)->with('report',$reportArray);
			} else {
				$reportArray['cat'] = 'Staff Commission Report';
				//$staff = FaceliftStawhereBetween('month',[$frommonth,$tomonth])->whereBetween('year',[$fromyear,$toyear])->get();
				return View::make('facelift.report.reportTemplate')->with('staff',$staff)->with('report',$reportArray);
			}
			/*************************Staff Services*********************************/
			} elseif($type == 'staff_services') {
				if($all == 1) {
					if($download == 1) {
						$lps2 = DB::table('faceliftstaff')->orderBy('name','ASC')->join('faceliftservices','faceliftstaff.staff_id','=','faceliftservices.staff_id')
									->join('faceliftcustomers','faceliftservices.client_id','=','faceliftcustomers.client_id')->get();
			
							$public = public_path();
							$record_folder = '\Records';
							$salesArray = array();
							if(!is_dir($public.$record_folder)) {
								mkdir('Records',0777);
								chdir('Records');
								
							} else {
								chdir('Records');
							}
								$file = strftime('%B-%d,%Y',strtotime('now')).'-Staff-Services-backup.csv';
								$filedir = '/'.$file;
								$c = 1;
								if(file_exists(getcwd().$filedir)) {
									chmod($file, 0777);
								}
							if($handle = fopen($file,'w+')) {
								$eachRow = array();
									$heading = [
											'S/N',
											'Date',
											'Time',
											'Staff Id',
											'Staff Name',
											'Service',
											'Service Start',
											'Service End',
											'Amount',
											'Client',
											'Client Phone',
											'Client Email'
											
									];
									$hd = implode(',', $heading);
									fwrite($handle, $hd."\r\n");
								foreach($lps2 as $lp) {
									$salesArray[] = $lp->amount;
									$eachRow[] = [
									$c++,
									strftime('%b %d %Y',strtotime($lp->created_at)),
									strftime('%H:%M:%S',strtotime($lp->created_at)),
									$lp->staff_id,
									$lp->name,
									str_replace(',','; ',$lp->service_type),
									//$lp->houseNo.' '.$lp->street.'; '.$lp->city.' '.$lp->location,
									$lp->service_start,
									$lp->service_end,
									$lp->amount,
									$lp->client_name,
									$lp->phone_no,
									$lp->email];
									
										}
										foreach ($eachRow as $key) {
											$str = implode(',',$key);
											fwrite($handle,$str."\r\n");
										}
										fwrite($handle,',,,,,,,,,,,,,Total,');
										fwrite($handle,array_sum($salesArray)."\r\n");
									
								
								fclose($handle);
								$link = link_to_asset('Records/'.$file,'Download');
								return $link;
							} else {
								return 'The file was not even close to open';
					}
				} else {
					$reportArray['cat'] = 'All Staff Services Report';
					return View::make('facelift.report.reportTemplate')->with('staff',$staff)->with('report',$reportArray);
					}
					
		
				} else {
				if($download == 1) {
						$lps2 = DB::table('faceliftservices')
						->whereBetween('faceliftservices.year',[$fromyear,$toyear])
						->whereBetween('faceliftservices.month',[$frommonth,$tomonth])
						->orderBy('name','ASC')
						->join('faceliftstaff','faceliftservices.staff_id','=','faceliftstaff.staff_id')
									->join('faceliftcustomers','faceliftservices.client_id','=','faceliftcustomers.client_id')
									->get();
			
							$public = public_path();
							$record_folder = '\Records';
							$salesArray = array();
							if(!is_dir($public.$record_folder)) {
								mkdir('Records',0777);
								chdir('Records');
								
							} else {
								chdir('Records');
							}
								$file = strftime('%b(%Y)',strtotime($fromyear.'-'.$frommonth)).'-'.strftime('%b(%Y)',strtotime($toyear.'-'.$tomonth)).'-Staff-Services-backup.csv';
								$filedir = '/'.$file;
								$c = 1;
								if(file_exists(getcwd().$filedir)) {
									chmod($file, 0777);
								}
							if($handle = fopen($file,'w+')) {
								$eachRow = array();
									$heading = [
											'S/N',
											'Date',
											'Time',
											'Staff Id',
											'Staff Name',
											'Service',
											'Service Start',
											'Service End',
											'Amount',
											'Client',
											'Client Phone',
											'Client Email'
											
									];
									$hd = implode(',', $heading);
									fwrite($handle, $hd."\r\n");
								foreach($lps2 as $lp) {
									$salesArray[] = $lp->amount;
									$eachRow[] = [
									$c++,
									strftime('%b %d %Y',strtotime($lp->created_at)),
									strftime('%H:%M:%S',strtotime($lp->created_at)),
									$lp->staff_id,
									$lp->name,
									str_replace(',','; ',$lp->service_type),
									//$lp->houseNo.' '.$lp->street.'; '.$lp->city.' '.$lp->location,
									$lp->service_start,
									$lp->service_end,
									$lp->amount,
									$lp->client_name,
									$lp->phone_no,
									$lp->email];
									
										}
										foreach ($eachRow as $key) {
											$str = implode(',',$key);
											fwrite($handle,$str."\r\n");
										}
										fwrite($handle,',,,,,,,,,,,,,Total,');
										fwrite($handle,array_sum($salesArray)."\r\n");
									
								
								fclose($handle);
								$link = link_to_asset('Records/'.$file,'Download');
								return $link;
							} else {
								return 'OOps!!! The file was not even close to open';
					}
					
					} else {
						$reportArray['cat'] = 'Staff Services Report';
						return View::make('facelift.report.reportTemplate')->with('staff',$staff)->with('report',$reportArray);
						}
				}
				
			/*************************Staff Referrals*******************************/
			} elseif($type == 'staff_referrals') {
				if($all == 1) {
					$reportArray['cat'] = 'All Staff Referrals Report';
				} else {
					$reportArray['cat'] = 'Staff Referrals Report';
				}
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('staff',$staff)->with('referrals',$referrals);
/****************************Client Referrals**************************************************/
			} elseif($type == 'client_referrals'){
				if($all == 1) {
					$reportArray['cat'] = 'All Client Referrals Report';
				} else {
					$reportArray['cat'] = 'Client Referrals Report';
				}
					$referrals = FaceliftReferrals::all();
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients)->with('referrals',$referrals);
/****************************************Gender Breakdown***************************************************************/
			} elseif($type == 'gender_breakdown') {
				if($all == 1) {
					$reportArray['cat'] = 'All Gender Breakdown Report';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Gender Breakdown Report';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				}
				/****************************************BirthDays**************************************/
			} elseif($type == 'birthdays') {
				if($all == 1) {
					$reportArray['cat'] = 'All Birthdays Directory';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Birthday Directory';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				}
				//return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
/***********************************Wedding Anniversarries*******************************/
			} elseif($type == 'wedding_anniversaries') {
				if($all == 1) {
					$reportArray['cat'] = 'All Wedding Anniversary Directory';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Wedding Anniversary Directory';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				}
				//return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
/*********************************Client History ************************************/
			} elseif($type == 'client_history') {
				if($all == 1) {
					$reportArray['cat'] = 'All Clients Services History';
					$clients_history = FaceliftServices::all();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Clients Services History';
				$clients_history = FaceliftServices::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
			
				}
/***************************Sales Report*************************************/
			} elseif($type == 'sales_report') {
				if($all == 1) {
					if($download == 1) {
					$lps2 = DB::table('faceliftservices')->orderBy('faceliftservices.created_at','DESC')->join('faceliftcustomers','faceliftservices.client_id','=','faceliftcustomers.client_id')->get();
						//return dd($lps2);
					$public = public_path();
					$record_folder = '\Records';
					$salesArray = array();
					if(!is_dir($public.$record_folder)) {
						mkdir('Records',0777);
						chdir('Records');
						
					} else {
						chdir('Records');
					}
						$file = strftime('%B-%d,%Y',strtotime('now')).'-Sales-Report-backup.csv';
						$filedir = '/'.$file;
						$c = 1;
						if(file_exists(getcwd().$filedir)) {
							chmod($file, 0777);
							//return 'This file exists in the dir';
						}
					if($handle = fopen($file,'w+')) {
						$eachRow = array();
							$heading = [
									'S/N',
									'Date',
									'Time',
									'Client Name',
									'Service',
									'Service Start',
									'Service End',
									'Amount',
									'Client Phone',
									'Client Email'
									
							];
							$hd = implode(',', $heading);
							fwrite($handle, $hd."\r\n");
						foreach($lps2 as $lp) {
							$salesArray[] = $lp->amount;
							$eachRow[] = [
							$c++,
							strftime('%b %d %Y',strtotime($lp->created_at)),
							strftime('%H:%M:%S',strtotime($lp->created_at)),
							$lp->staff_id,
							$lp->name,
							str_replace(',','; ',$lp->service_type),
							//$lp->houseNo.' '.$lp->street.'; '.$lp->city.' '.$lp->location,
							$lp->service_start,
							$lp->service_end,
							$lp->amount,
							$lp->client_name,
							$lp->phone_no,
							$lp->email
							];
							
								}
								foreach ($eachRow as $key) {
									$str = implode(',',$key);
									fwrite($handle,$str."\r\n");
								}
								fwrite($handle,',,,,,,,,,,,,,Total,');
								fwrite($handle,array_sum($salesArray)."\r\n");
							
						
						fclose($handle);
						$link = link_to_asset('Records/'.$file,'Download');
						return $link;
					} else {
						return 'The file was not even close to open';
					}
				}
					else {
					$reportArray['cat'] = 'All Sales Report';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices);
					}
				
				} else {
					$reportArray['cat'] = 'Sales Report';
					$allServices = FaceliftServices::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices);
				}
				/***************PreBooked dates***********************************/
			} elseif($type == 'pre_booked_dates') {
				if($all == 1) {
					$reportArray['cat'] = 'All Pre-booked Dates';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices);
				} else {
					$reportArray['cat'] = 'Prebooked Dates Report';
					$allServices = FaceliftServices::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices);
				}
/********************************Profit and Loss************************/
			} elseif ($type == 'profit_loss') {
				if($all == 1) {
				$reportArray['cat'] = 'All Income and Expenditure Report';
				$allExpenses = FaceliftExpenseControl::orderBy('created_at','DESC')->get();
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices)->with('allExpenses',$allExpenses);
			}
			else {
				$reportArray['cat'] = 'Profit and Loss Report';
				$allServices = FaceliftServices::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
				$allExpenses = FaceliftExpenseControl::orderBy('created_at','DESC')->whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices)->with('allExpenses',$allExpenses);
			
			}
/***********************************Phone Numbers*******************/
			} elseif($type == 'phone_numbers') {
				if($all == 1) {
					$reportArray['cat'] = 'All Phone Numbers Directory';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Phone Numbers Directory';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				}
				//return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
/*****************************Emails***************************/
			} elseif ($type == 'emails') {
				if($all == 1) {
					$reportArray['cat'] = 'All Phone Numbers Directory';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Phone Numbers Directory';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
							}
/********************************Loyalty Points ***************/
			} elseif($type == 'loyalty_points') {
				if($all == 1) {
					$reportArray['cat'] = 'All Loyalty Points';
				$lp = FaceliftLoyaltyPoints::orderBy('created_at','DESC')->get();
				
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('lp',$lp)->with('clients',$clients);
			} else {
				$reportArray['cat'] = 'Loyalty Points';
				$lp = FaceliftLoyaltyPoints::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->orderBy('created_at','DESC')->get();
				
				return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('lp',$lp)->with('clients',$clients);
			}
/******************************Client Info***********************/
			} elseif($type == 'client_info') {
				if($all == 1) {
					$reportArray['cat'] = 'All Clients Directory';
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				} else {
					$reportArray['cat'] = 'Clients Directory';
					$clients = FaceliftCustomers::whereBetween('year',[$fromyear,$toyear])->whereBetween('month',[$frommonth,$tomonth])->get();
					return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('clients',$clients);
				}
/*****************************Expense Control********************/
			} elseif($type == 'expense_control') {
				if($all == 1) {
					$reportArray['cat'] = 'All Expenses';
					$budgCat = FaceliftBudgetCategories::all();
				
				$expenses = DB::table('faceliftcontrol')->get();
			return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('categories',$budgCat)->with('expenses',$expenses);
			
		} else {
			$reportArray['cat'] = 'Expenses';
				$budgCat = FaceliftBudgetCategories::all();
				
				$expenses = DB::table('faceliftcontrol')->whereBetween('month', [$frommonth, $tomonth] )->whereBetween('year',[$fromyear,$toyear])->get();
			return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('categories',$budgCat)->with('expenses',$expenses);
			}
			
			} elseif($type == 'todaysReport') {
				//$reportArray['cat'] = 'All Profit and Loss Report';
				
				//return View::make('facelift.report.reportTemplate')->with('report',$reportArray)->with('allServices',$allServices)->with('allExpenses',$allExpenses);
				$mainHd = [
					'Sales',
					',',
					',',
					',',
					',',
					',',
					',',
					',',
					',',
					',',
					',',
					',',
					
				];
				$hd = [
					'',
					'S/N',
					'Date',
					'Time',
					'Service',
					'Service Start',
					'Service End',
					'Amount',
					'Attendant',
					'arrival',
					'departure',
					'Client',
					
				];
				$allExpenses = FaceliftExpenseControl::orderBy('created_at','DESC')->get();
				$allServices = FaceliftServices::orderBy('created_at','DESC')->get();
				$serviceArray = array();
				$expensesArray = array();
				$totalservices = array();
				$totalExp = array();
				$c = 1;
				$d = 1;
				foreach($allServices as $eachService) {
					if(strftime('%Y-%m-%d',strtotime($eachService->created_at)) == strftime('%Y-%m-%d',strtotime('today'))) {
						$client = FaceliftCustomers::find($eachService->client_id)->first();
						$totalservices[] = $eachService->amount;
						$serviceArray[] = [
						'',
						$c++,
						strftime('%Y-%m-%d',strtotime($eachService->created_at)),
						strftime('%H:%M:%S',strtotime($eachService->created_at)),
						str_replace(',', ';', $eachService->service_type),
						$eachService->service_start,
						$eachService->service_end,
						$eachService->amount,
						$eachService->attendant,
						$eachService->arrival,
						$eachService->departure,
						$client->client_name
					];
					}
				}
				foreach($allExpenses as $exp) {
					if(strftime('%Y-%m-%d',strtotime($exp->created_at)) == strftime('%Y-%m-%d',strtotime('today'))) {
						$cat = FaceliftBudgetCategories::find($exp->category_id)->first();
						$totalExp[] = $exp->amount;
						$expensesArray[] = [
							'',
							$c++,
							strftime('%Y-%m-%d',strtotime($eachService->created_at)),
							strftime('%H:%M:%S',strtotime($eachService->created_at)),
							$cat->category,
							$exp->amount
						];
					}
				}
				$public = public_path();
					$record_folder = '\Records';
					$salesArray = array();
					if(!is_dir($public.$record_folder)) {
						mkdir('Records',0777);
						chdir('Records');
						
					} else {
						chdir('Records');
					}
						$file = strftime('%B-%d,%Y',strtotime('now')).'-Sales-report.csv';
						$filedir = '/'.$file;
						$c = 1;
						if(file_exists(getcwd().$filedir)) {
							chmod($file, 0777);
							//return 'This file exists in the dir';
						}
						if($handle = fopen($file,'w+')) {
							$strhd = implode('',$mainHd);
							fwrite($handle,$strhd."\r\n");
							$strSub = implode(',',$hd);
							fwrite($handle,$strSub."\r\n");
							//return dd($serviceArray);
							foreach($serviceArray as $srv) {
						$st = implode(',', $srv);
						fwrite($handle,$st."\r\n");
								}
							$expMainAr = ["\r\n","\r\n","Expenses","\r\n"];
							$expHd = [
								'',
								'S/N',
								'Date',
								'Time',
								'Expense',
								'Amount'
							];
							$strM = implode('', $expMainAr);
							fwrite($handle, $strM);
							$strHd = implode(',', $expHd)."\r\n";
							fwrite($handle,$strHd);
							//return dd($expensesArray);
							foreach($expensesArray as $expA) {
								$strExp = implode(',', $expA)."\r\n";
								fwrite($handle,$strExp);
							}
							$A = ["\r\n","\r\n","Total","\r\n"];
							$B = ["","Total Sales","Total Expenses","Balance"];
							$totals = ['',array_sum($totalservices),array_sum($totalExp),array_sum($totalservices) - array_sum($totalExp)];
							fwrite($handle, implode('',$A));
							fwrite($handle, implode(',',$B)."\r\n");
							fwrite($handle, implode(',',$totals)."\r\n");
							fclose($handle);
						}
						$link = link_to_asset('/Records/'.$file,'Download '.$file);
						return $link;
			}
			else {
				return Redirect::back()->with('info','No report Category Chosen');
			}
	} else {
		return Redirect::back()->with('info', 'You don\'t have access to this Page');
	}
}
///adding lotalty Points
	Public function postAddLoyaltyPoints() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$rules = [
			'client_id' => 'required|exists:faceliftcustomers,client_id',
			'loyalty-points' => 'required'
		];
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::back()->withErrors($v)->withInput();
		}
		$client = FaceliftCustomers::find(Input::get('client_id'));
		$lp = new FaceliftLoyaltyPoints();
		$lp = $lp->customer()->associate($client);
		$lp->loyalty_points = Input::get('loyalty-points');
		$lp->month = strftime('%m',strtotime('today'));
		$lp->year = strftime('%Y',strtotime('today'));
		if($lp->save()) {
			return Redirect::back()->with('report','Loyalty Points Saved');
		}
	}
	Public function getLoyaltyPoints() {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$lp = FaceliftLoyaltyPoints::orderBy('created_at','DESC')->get();
		return View::make('facelift.loyaltyPoints')->with('lp',$lp);
	}
//deleting staff
	Public function postDeleteStaff($staff_id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$staff = FaceliftStaff::find($staff_id);
			//dd($staff);
			if($staff) {
				FaceliftStaff::find($staff->staff_id)->delete();
				//dd($staff);
					return Redirect::back()->with('info','Staff Delete Complete');
				
			} else {
				return Redirect::back()->with('bad-report','Staff was not found in Record');
			}
		} else {
			return Redirect::back()->with('info','You do not have access to this page');
		}
	}
	Public function postDeactivate($staff_id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$staff = FaceliftStaff::find($staff_id);
			//dd($staff);
			if($staff) {
				$deactivateStaff = FaceliftStaff::find($staff->staff_id);
				
				$deactivateStaff->deleted_at = strftime('%Y-%m-%d %H:%M:%S',strtotime('now'));
				if($deactivateStaff->save()) {
					return Redirect::back()->with('info','Staff Deactivated');
				} else {
					return Redirect::back()->with('bad-report','Staff Delete Unsuccessful; Please Try Again');
				}
				
			} else {
				return Redirect::back()->with('bad-report','Staff was not found in Record');
			}
		} else {
			return Redirect::back()->with('info','You do not have access to this page');
		}
	}
	Public function postActivate($staff_id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
			$staff = FaceliftStaff::find($staff_id);
			//dd($staff);
			if($staff) {
				$deactivateStaff = FaceliftStaff::find($staff->staff_id);
				
				$deactivateStaff->deleted_at = 'NULL';
				if($deactivateStaff->save()) {
					return Redirect::back()->with('info','Staff Activated');
				} else {
					return Redirect::back()->with('bad-report','Staff Activation Unsuccessful; Please Try Again');
				}
				
			} else {
				return Redirect::back()->with('bad-report','Staff was not found in Record');
			}
		} else {
			return Redirect::back()->with('info','You do not have access to this page');
		}
	}
	//deleting users
	Public function postUserDelete($username) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		if(Auth::user()->super_admin == 1) {
			$user = FaceliftUsers::where('username','=',$username)->first();
			//dd($staff);
			if($user) {
				FaceliftUsers::find($user->id)->delete();
				
					return Redirect::back()->with('info','User Delete Complete');
				
			} else {
				return Redirect::back()->with('bad-report','User was not found in Record');
			}
		} else {
			return Redirect::back()->with('info','You do not have access to this page');
		}
	}
//Deleting Services
Public function postDeleteService($id) {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		if($id == 'NULL') {
		return Redirect::back()->with('info','Service Id was not found');
	}
		$service = DB::table('faceliftservices')->where('id','=',$id)->first();
		if(count($service)) {
			if(DB::table('faceliftservices')->where('id','=',$id)->delete()) {
			return Redirect::back()->with('info','Service Delete Successfull');
			} else {
				return Redirect::back()->with('info','Service Delete Failed');
			}
		
		} else {
			return Redirect::back()->with('info','Service was not found');
		}
	} else {
		return Redirect::back()->with('info','You dont have access to perform this operation');
	}
	
}
//send mail
Public function postSend() {
	if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
	}
	if(Auth::user()->admin == 1 || Auth::user()->super_admin == 1) {
		$rules = [
					'backup'=>'required'
					];
		//return Input::file('backup')->getRealPath();
		$v = Validator::make(Input::all(), $rules);
		if($v->fails()) {
			return Redirect::to('facelift/report')->withErrors($v);
		}
		$data = [
			'name'=>'Tijesunimi'
		];
		$file = Input::file('backup');
		//return $file->getClientOriginalName();
		
		Mail::send(['text'=>'facelift.layouts.email'], $data, function($message) {
			$realfilename = Input::file('backup')->getClientOriginalName();
			$message->from('faceliftbeautypalace@yahoo.com','Facelift Beauty Palace');
			$message->to('sunkanmiadekeye@gmail.com')->subject('Facelift Beauty Palace BackUp');
			$message->attach(public_path().'/Records/'.$realfilename);
		});
		return Redirect::back()->with('info','Mail Sent');
	} else {
		return Redirect::back()->with('info','You dont have the access to perform this operation');
	}
}
//Generate Backup
	Public function postCreateBackup() {
		if(!Auth::check()) {
		return Redirect::to('facelift/user-login');
		}
		
		$services = FaceliftServices::all();
		$expenses = FaceliftExpenseControl::all();
		$referrals = FaceliftReferrals::all();
		$staff = FaceliftStaff::all();
		$staffCommission = FaceliftStaffCommission::all();
		$user = FaceliftUsers::all();
		
			$links = array();
			$allFiles = array();
		$public = public_path();
		$record_folder = '\Records';
		if(!is_dir($public.$record_folder)) {
			mkdir('Records',0777);
			chdir('Records');
			
		} else {
			chdir('Records');
		}
			
			$fileLp = strftime('%B-%d,%Y',strtotime('now')).'-Loyalty_Points-backup.csv';
			$filedir = '/'.$fileLp;
			$c = 1;
			if(file_exists(getcwd().$filedir)) {
				chmod($fileLp, 0777);
				//return 'This file exists in the dir';
			}
			if($Lpfile = fopen($fileLp,'w+')) {
				$eachLpRow = array();
				$lp = FaceliftLoyaltyPoints::all();
				$headingLp = [
						'id',
						'client_id',
						'loyalty_points',
						'created_at',
						'updated_at',
						'month',
						'year'
				];
				$head = implode(',', $headingLp);
				fwrite($Lpfile, $head."\r\n");
				foreach($lp as $Lp) {
					$eachLpRow[] = [
							$Lp->id,
							$Lp->client_id,
							$Lp->loyalty_points,
							$Lp->created_at,
							$Lp->updated_at,
							$Lp->month,
							$Lp->year
					];
				}
				foreach($eachLpRow as $row) {
					$rowStr = implode(',', $row);
					fwrite($Lpfile,$rowStr."\r\n");
				}
				fclose($Lpfile);
				$links['LP'] = link_to_asset('Records/'.$fileLp,'DownLoad Loyalty-Points Backup');
				$allFiles[] = $fileLp;
					
			}
/***********************************Budgets*************************************************************/
		$fileBud = strftime('%B-%d,%Y',strtotime('now')).'-Budgets-backup.csv';
			$filedir = '/'.$fileBud;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileBud, 0777);
				//return 'This file exists in the dir';
			}
			if($Budgfile = fopen($fileBud,'w+')) {
				$budget = FaceliftBudget::all();
				$budgetArray = array();
				$headingBudget = [
						'id',
						'month',
						'year',
						'budget',
						'category',
						'created_at',
						'updated_at',
						'month_year',
						'category_id'
				];
				$hd = implode(',', $headingBudget);
				fwrite($Budgfile, $hd."\r\n");
				foreach($budget as $Lp) {
					$budgetArray[] = [
							$Lp->id,
							$Lp->month,
							$Lp->year,
							$Lp->budget,
							$Lp->category,
							$Lp->created_at,
							$Lp->updated_at,
							$Lp->month_year,
							$Lp->category_id
					];
				}
				foreach ($budgetArray as $key) {
					$str = implode(',',$key);
					fwrite($Budgfile, $str."\r\n");
				}
				fclose($Budgfile);
				$links['budget'] = link_to_asset('Records/'.$fileBud,'DownLoad Budget Backup');
				$allFiles[] = $fileBud;
			}
/**********************************Customers**************************************************/
			$fileCust = strftime('%B-%d,%Y',strtotime('now')).'-Customers-backup.csv';
			$filedir = '/'.$fileCust;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileCust, 0777);
				//return 'This file exists in the dir';
			}
			if($custfile = fopen($fileCust,'w+')) {
				$customers = FaceliftCustomers::all();
				$customersArray = array();
				$headingCustomers = [
						'id',
						'client_id',
						'client_type',
						'client_name',
						'gender',
						'houseNo',
						'street',
						'location',
						'city',
						'religion',
						'status',
						'wedding_anniversary',
						'DOB',
						'occupation',
						'phone_no',
						'email',
						'flk',
						'created_at',
						'updated_at',
						'month',
						'year'
						];
				$hd = implode(',', $headingCustomers);
				fwrite($custfile, $hd."\r\n");
				foreach($customers as $customer) {
					$customersArray[] = [
							$customer->id,
							$customer->client_id,
							$customer->client_type,
							$customer->client_name,
							$customer->gender,
							$customer->houseNo,
							$customer->street,
							$customer->location,
							$customer->city,
							$customer->religion,
							$customer->status,
							$customer->wedding_anniversary,
							$customer->DOB,
							$customer->occupation,
							$customer->phone_no,
							$customer->email,
							$customer->flk,
							$customer->created_at,
							$customer->updated_at,
							$customer->month,
							$customer->year
					];
				}
				foreach ($customersArray as $key) {
					$str = implode(',',$key);
					fwrite($custfile, $str."\r\n");
				}
				fclose($custfile);
				$links['customers'] = link_to_asset('Records/'.$fileCust,'DownLoad Customers Backup');
				$allFiles[] = $fileCust;
			}
/********************************Budget Categories **********************************/
					
			$fileBudgCat = strftime('%B-%d,%Y',strtotime('now')).'-Budget-Categories-backup.csv';
			$filedir = '/'.$fileBudgCat;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileBudgCat, 0777);
				//return 'This file exists in the dir';
			}
			if($BC = fopen($fileBudgCat,'w+')) {
				$budgetCat = FaceliftBudgetCategories::all();
				$BCarray = array();
				$headingBudCat = [
						'id',
						'category_id',
						'category',
				];
				$hd = implode(',', $headingBudCat);
				fwrite($BC, $hd."\r\n");
				foreach ($budgetCat as $key) {
					$BCarray[] = [
						$key->id,
						$key->category_id,
						$key->category,
					];
				}
				foreach ($BCarray as $keystr) {
					$str = implode(',', $keystr);
					fwrite($BC,$str."\r\n");
				}
				fclose($BC);
				$links['BC'] = link_to_asset('Records/'.$fileBudgCat,'DownLoad Budget Categories BackUp');
				$allFiles[] = $fileBudgCat;
			}
/****************************Expenses****************************/
		$fileEC = strftime('%B-%d,%Y',strtotime('now')).'-Expenses-backup.csv';
			$filedir = '/'.$fileEC;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileEC, 0777);
				//return 'This file exists in the dir';
			}
			if($EC = fopen($fileEC,'w+')) {
				$expenses = FaceliftExpenseControl::all();
				$expAr = array();
				$headingexpenses = [
						'id',
						'type',
						'particulars',
						'amount',
						'staff',
						'approval',
						'user_unit',
						'month',
						'year',
						'created_at',
						'updated_at',
						'category_id'
				];
				$hd = implode(',', $headingexpenses);
				fwrite($EC,$hd."\r\n");
				foreach($expenses as $exp) {
					$expAr[] = [
						$exp->id,
						$exp->type,
						$exp->particulars,
						$exp->amount,
						$exp->staff,
						$exp->approval,
						$exp->user_unit,
						$exp->month,
						$exp->year,
						$exp->created_at,
						$exp->updated_at,
						$exp->category_id
					];
				}
				foreach ($expAr as $key) {
					$str = implode(',', $key);
					fwrite($EC,$str."\r\n");
				}
				fclose($EC);
				$links['EC'] = link_to_asset('Records/'.$fileEC,'DownLoad EC BAckup');
				$allFiles[] = $fileEC;
			}
/********************************Referrals************************************/
		$fileRef = strftime('%B-%d,%Y',strtotime('now')).'-Referrals-backup.csv';
			$filedir = '/'.$fileRef;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileRef, 0777);
				//return 'This file exists in the dir';
			}
			if($Ref = fopen($fileRef,'w+')) {
				$referrals = FaceliftReferrals::all();
				$RefAr = array();
				$headingRef = [
						'id',
						'client_id',
						'refered',
						'created_at',
						'updated_at',
						'staff_id',
						'customer_id',
						'month',
						'year'
				];
				$hd = implode(',', $headingRef);
				fwrite($Ref,$hd."\r\n");
				foreach($referrals as $ref) {
					$RefAr[] = [
						$ref->id,
						$ref->client_id,
						$ref->refered,
						$ref->created_at,
						$ref->updated_at,
						$ref->staff_id,
						$ref->customer_id,
						$ref->month,
						$ref->year
						
						
					];
				}
				foreach ($RefAr as $keys) {
					$str = implode(',', $keys);
					fwrite($Ref,$str."\r\n");
				}
				fclose($Ref);
				$links['ref'] = link_to_asset('Records/'.$fileRef,'DownLoad Ref Backup');
				$allFiles[] = $fileRef;
			}
/***********************************Services******************************/
		$fileServe = strftime('%B-%d,%Y',strtotime('now')).'-Services-backup.csv';
			$filedir = '/'.$fileServe;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileServe, 0777);
				//return 'This file exists in the dir';
			}
			if($S = fopen($fileServe,'w+')) {
				$services = FaceliftServices::all();
				$SA = array();
				$headingServices = [
						'id',
						'service_type',
						'service_start',
						'service_end',
						'attendant',
						'amount',
						'arrival',
						'departure',
						'rebooked_date',
						'client_id',
						'staff_id',
						'created_at',
						'updated_at',
						'month',
						'year'
				];
				$hd = implode(',', $headingServices);
				fwrite($S,$hd."\r\n");
				foreach($services as $serve) {
					$SA[] = [
						$serve->id,
						str_replace(',', ';', $serve->service_type),
						$serve->service_start,
						$serve->service_end,
						$serve->attendant,
						$serve->amount,
						$serve->arrival,
						$serve->departure,
						$serve->rebooked_date,
						$serve->client_id,
						$serve->staff_id,
						$serve->created_at,
						$serve->updated_at,
						$serve->month,
						$serve->year
						
						
					];
				}
				foreach ($SA as $key) {
					$str = implode(',', $key);
					fwrite($S,$str."\r\n");
				}
				fclose($S);
				$links['services'] = link_to_asset('Records/'.$fileServe,'DownLoad service Backup');
				$allFiles[] = $fileServe;
			}
/************************************Staff******************************************/
	$fileStaff = strftime('%B-%d,%Y',strtotime('now')).'-Staff-backup.csv';
			$filedir = '/'.$fileStaff;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileStaff, 0777);
				//return 'This file exists in the dir';
			}
			if($St = fopen($fileStaff,'w+')) {
				$staffs = FaceliftStaff::all();
				$Sa = array();
				$headingStaff = [
						'id',
						'staff_id',
						'name',
						'address',
						'phone_no',
						'email',
						'created_at',
						'updated_at',
						'deleted_at',
						'month',
						'year'
				];
				$hd = implode(',', $headingStaff);
				fwrite($St,$hd."\r\n");
				foreach($staffs as $staff) {
					$Sa[] = [
						$staff->id,
						$staff->staff_id,
						$staff->name,
						$staff->address,
						$staff->phone_no,
						$staff->email,
						$staff->created_at,
						$staff->updated_at,
						$staff->deleted_at,
						$staff->month,
						$staff->year
						
						
					];
				}
				foreach ($Sa as $key) {
					$str = implode(',', $key);
					fwrite($St,$str."\r\n");
				}
				fclose($St);
				$links['staff'] = link_to_asset('Records/'.$fileStaff,'DownLoad staff Backup');
				$allFiles[] = $fileStaff;
			}
/***********************Staff Commissions *****************************/
		$fileSC = strftime('%B-%d,%Y',strtotime('now')).'-Staff-Commissions-backup.csv';
			$filedir = '/'.$fileSC;
		if(file_exists('.'.$filedir)) {
				chmod($fileSC, 0777);
				//return 'This file exists in the dir';
			}
			if($SC = fopen($fileSC,'w+')) {
				$staffCom = FaceliftStaffCommission::all();
				$scA = array();
				$headingStaffCom = [
						'id',
						'staff_id',
						'commission',
						'customer_id',
						'services',
						'amount',
						'date',
						'total',
						'created_at',
						'updated_at',
						'month',
						'year'
				];
				$hd = implode(',', $headingStaffCom);
				fwrite($SC,$hd."\r\n");
				foreach($staffCom as $staff) {
					$scA[] = [
						$staff->id,
						$staff->staff_id,
						$staff->commission,
						$staff->customer_id,
						str_replace(',',';',$staff->services),
						$staff->amount,
						$staff->date,
						$staff->total,
						$staff->created_at,
						$staff->updated_at,
						$staff->month,
						$staff->year
						
						
					];
				}
				foreach ($scA as $key) {
					$str = implode(',', $key);
					fwrite($SC,$str."\r\n");
				}
				fclose($SC);
				$links['StaffCom'] = link_to_asset('Records/'.$fileSC,'DownLoad Commissions Backup');
			}
/***************************Users ***************************************************/
		$fileUsers = strftime('%B-%d,%Y',strtotime('now')).'-Users-backup.csv';
			$filedir = '/'.$fileUsers;
		if(file_exists(getcwd().$filedir)) {
				chmod($fileUsers, 0777);
				//return 'This file exists in the dir';
			}
			if($U = fopen($fileUsers,'w+')) {
				$Users = FaceliftUsers::all();
				$uA = array();
				$headingUsers = [
						'id',
						'username',
						'password',
						'admin',
						'super_admin',
						'remember_token',
						'created_at',
						'updated_at',
				];
				$hd = implode(',', $headingUsers);
				fwrite($U,$hd."\r\n");
				foreach($Users as $u) {
					$uA[] = [
						$u->id,
						$u->username,
						$u->password,
						$u->admin,
						$u->super_admin,
						$u->remember_token,
						$u->created_at,
						$u->updated_at,
						$u->deleted_at
						
						
					];
				}
				foreach ($uA as $key) {
					$str = implode(',', $key);
					fwrite($U,$str."\r\n");
				}
				fclose($U);
				$links['users'] = link_to_asset('Records/'.$fileUsers,'DownLoad Users Backup');
				$allFiles[] = $fileUsers;
			}
			
			//return dd($links);
				$zip = new ZipArchive();
				$filename = 'FLBPBackup'.strftime('%Y%m%d',strtotime('today')).'.zip';
				if(file_exists('./'.$filename)) {
					unlink('./'.$filename);
				}
				if($zip->open('./'.$filename,ZipArchive::CREATE) !== TRUE) {
					
					return Redirect::back()->with('info','Database Backup Failed; Please Try again');
				} else {
					foreach ($allFiles as $key) {
						$zip->addFile($key);
					}
						
					
									$zip->close();
				}
			return link_to_asset('/Records/'.$filename,'Download Backup');
		
	}
	Public function postWeeklyReport() {
		/*This is the weekly report*/
		$date = strftime("%Y-%m-%d",strtotime('-1week'));
		$allServices = FaceliftServices::where(DB::raw('DATE(created_at)'),'>',$date)->get();
		$report = [];
		$report['cat'] = 'Weekly Report';
		$report['from'] = $date;
		$report['to'] = strftime('%Y-%m-%d',strtotime('today'));
		$report['all'] = 0;
		
		return Response::view('facelift.report.weeklyReport',['allServices'=>$allServices,'report'=>$report]);
	}
	Public function getWeeklyReport() {
		/*This is going to be doing nothing*/
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		return Redirect::to('facelift/report');
	}

Public function getViewExpenses() {
		if(!Auth::check() || Auth::user()->super_admin != 1 ) {
			Session::flash('info','You are not allowed to view this page');
			return Redirect::back();
		}
		$allExp = FaceliftControls::where('month','=',strftime('%m'))->where('year','=',strftime('%Y'))->get();

		return View::make('facelift.viewExpenses')->with('allExpenses',$allExp);
	}

	Public function getEditExpenses($id) {
		if(!Auth::check()) {
			return Redirect::to('facelift/user-login');
		}
		$allExp = FaceliftControls::where('id','=',$id)->first();
		return View::make('facelift.expensesModal')->with('expense',$allExp)->with('id',$id);
	}

	Public function postEditExpenses($id) {
		if(!Auth::check() && Auth::user()->super_admin != 1) {
			return Redirect::to('facelift/user-login');
		}

		$exp = FaceliftControls::where('id','=',$id)->first();


		$model = FaceliftControls::find($id);
		$model->particulars = Input::get('particulars') ? Input::get('particulars') : $exp->particulars;
		$model->staff = Input::get('staff') ? Input::get('staff') : $exp->staff;
		$model->approval = Input::get('approval') ? Input::get('approval') : $exp->approval;
		$model->amount = Input::get('amount') ? Input::get('amount') : $exp->amount;
		$model->user_unit = Input::get('user_unit') ? Input::get('user_unit') : $exp->user_unit;

		if($model->save()) {
			Session::flash('info','Expense Saved');
			return Redirect::back();
		} else {
			Session::flash('info','Expense Not saved');
			return Redirect::back();
		}




	}

	

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

					if(!empty($combinedArray['name'])) {
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
		// echo '<pre>';
		// print_r($combinedArray);
		// echo '</pre>';
			
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

				$combinedArray['created_at'] = strftime('%Y-%m-%d 09:00:00',strtotime(str_replace('/','-',$combinedArray['date'])));

				$model = new FaceliftExpenseControl;
				$chckExpense = $model->where('created_at','=',$combinedArray['created_at'])->where('particulars','=',$combinedArray['description'])->where('approval','=',$combinedArray['approval'])->where('staff','=',$combinedArray['purchased by'])->get()->toArray();
				$cat = FaceliftBudgetCategories::where('category','like',$combinedArray['type'])->get();
				$combinedArray['cat_id'] = $cat[0]->category_id;

				if(empty($chckExpense) && !empty($cat)) {
					$re = $model->addExpenseFromFile($combinedArray);
						if( $re ) {
							$this->report['success'][] = 'Expense at row '.$combinedArray['s/no'].' added succesfully';
						} else {
							$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' failed to add to database';
						}
				// 	}
				} elseif(!empty($chckExpense)) {
					$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' already saved database';
				} else {
					$this->report['error'][] = 'Expense at row '.$combinedArray['s/no'].' failed to add to database - unknown error';
				}
				
			} else {
				$this->report['error'][] = 'Expense at row empty';
			}

			
		}

		return Redirect::back()->with('error',$this->report['error'])->with('success',$this->report['success']);

		// echo "<pre>";
		// 		print_r($combinedArray);
		// 		echo "</pre>";
	}

	public function getTe() {
		// $c = FaceliftControls::where('month','=',11)->where('year','=',2015)->groupBy('user_unit')->orderBy('created_at','desc')->get()->toArray();
		// $c = DB::select("select created_at, month, year, SUM(amount) as amount, user_unit as Unit, approval from faceliftcontrol where month = ? and year = ? group by user_unit order by created_at desc", [11,2015]);
		$c = Auth::user();
		echo "<pre>";
		print_r($c);
		echo "</pre>";
	}

	public function getViewExpensesByUnit() {
		$month = strftime('%m',strtotime('this month'));
		$year = strftime('%Y',strtotime('this year'));
		$c = DB::select("select particulars, created_at, month, year, SUM(amount) as amount, user_unit, approval from faceliftcontrol where month = ? and year = ? group by user_unit order by created_at desc", [$month,$year]);


		return Response::view('facelift.viewExpensesByUnit',['allExpenses'=>$c]);
	}
} // end for The Class