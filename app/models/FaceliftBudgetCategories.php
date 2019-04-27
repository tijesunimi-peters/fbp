<?php

Class FaceliftBudgetCategories extends Eloquent {



	protected $table = 'faceliftbudgetcategories';
	protected $primaryKey = 'category_id';

	Public function budgets() {
		return $this->hasMany('FaceliftBudget','category_id');
	}

	Public function expenses() {
		return $this->hasMany('FaceliftExpenseControl','category_id');
	}





















}