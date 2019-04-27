<?php

class FaceliftReferrals extends Eloquent {

	protected $table = 'faceliftreferrals';



	public function staff() {
		return $this->belongsTo('FaceliftStaff','staff_id');
	}

	public function customer() {
		return $this->belongsTo('FaceliftCustomers','customer_id');
	}



}