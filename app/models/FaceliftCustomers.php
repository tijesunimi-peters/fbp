<?php

Class FaceliftCustomers extends Eloquent {

	protected $table = 'faceliftcustomers';

	protected $primaryKey = 'client_id';

	public function services() {
		return $this->hasMany('FaceliftServices','client_id');
	}

	public function referrals() {
		return $this->hasMany('FaceliftReferrals','customer_id');
	}

	public function loyalty_points() {
		return $this->hasMany('FaceliftLoyaltyPoints','client_id');
	}

	Public Function addCustomers($array) {
		// $model = new FaceliftCustomers;
		$num = rand(1000,4000);
		// $clientID = $this->find($num) ? $num : 
		$this->client_id = rand(1000,4000);
		$this->client_type = isset($array['referred']) ? 'referral' : 'walk-in';
		$this->client_name = isset($array['name']) ? $array['name'] : '';
		$this->gender = isset($array['gender']) ? $array['gender'] : '';
		$this->street = isset($array['address']) ? $array['address'] : '';
		$this->religion = isset($array['religion']) ? $array['religion'] : '';
		$this->status = isset($array['marital status']) ? $array['marital status'] : '';
		$this->wedding_anniversary = isset($array['wedding anniversary']) ? strftime("%Y-%m-%d",strtotime($array['wedding anniversary'])) : '';
		$this->DOB = isset($array['month & date of birth']) ? strftime("%Y-%m-%d", strtotime($array['month & date of birth'])) : '';
		$this->occupation = isset($array['occupation']) ? $array['occupation'] : '';
		$this->phone_no = isset($array['phone no']) ? $array['phone no'] : '';
		$this->email = isset($array['email']) ? $array['email'] : '';
		$this->referee = isset($array['referred']) ? $array['referred'] : '';
		$this->flk= isset($array['facelift knowledge']) ? $array['facelift knowledge'] : '';
		$this->month = isset($array['date']) ? strftime('%m',strtotime($array['date'])) : '';
		$this->year = isset($array['date']) ? strftime('%Y',strtotime($array['date'])) : '';

		if(!empty($array['name'])) {
			if($this->save()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		
	}

	Public function getCustomerInfo() {
		// return $this->where('')
	}

}