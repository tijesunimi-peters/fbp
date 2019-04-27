<?php

Class FaceliftStaffCommission extends Eloquent {

	protected $table = 'faceliftstaffcommission';
	protected $primaryKey = 'staff_id';


	public function staff() {
		return $this->belongsTo('FaceliftStaff','staff_id');
	}

	public function customer() {
		return $this->hasOne('FaceliftCustomers','customer_id');
	}



	

















}