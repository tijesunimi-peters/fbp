<?php

Class FaceliftBudget extends Eloquent {

	protected $table = 'faceliftbudget';

	public function category() {
		return $this->belongsTo('FaceliftBudgetCategories','category_id');
	}

	public function expenses() {
		//return $this->hasMany('FaceliftExpenses',strftime('%Y-%m',strtotime()))
	}

}