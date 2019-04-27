<?php

Class FaceliftStaff extends Eloquent {
	protected $table = 'faceliftstaff';
	protected $primaryKey = 'staff_id';
	//protected $softDelete = 'true';

	public function services() {
		return $this->hasMany('FaceliftServices','staff_id');
	}

	public function referral() {
		return $this->hasMany('FaceliftReferrals','staff_id');
	}

	public function commission() {
		return $this->hasMany('FaceliftStaffCommission','staff_id');
	}


}