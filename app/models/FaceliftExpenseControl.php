<?php

Class FaceliftExpenseControl extends Eloquent {

	protected $table = "faceliftcontrol";

	protected $primaryKey = 'category_id';


	public function addExpenseFromFile($array) {
		
		
		
			$this->type = $array['cat_id'];
			$this->category_id = $array['cat_id'];
			$this->staff = $array['purchased by'];
			$this->particulars = $array['description'];
			$this->amount = $array['amount'];
			$this->created_at = $array['created_at'];
			$this->approval = $array['approval'];
			$this->user_unit = $array['user unit'];
			$this->month = strftime("%m",strtotime($array['created_at']));
			$this->year = strftime("%Y",strtotime($array['created_at']));

			if($this->save()) {
				return true;
			} else {
				return false;
			}
		
	}


}