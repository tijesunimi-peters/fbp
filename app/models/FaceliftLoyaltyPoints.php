<?php

Class FaceliftLoyaltyPoints extends Eloquent {


	protected $table = 'faceliftlp';

	

	Public function customer() {
		return $this->belongsTo('FaceliftCustomers','client_id');
	}






}