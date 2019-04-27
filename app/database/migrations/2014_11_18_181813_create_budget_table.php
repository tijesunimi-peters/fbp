<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftbudget', function($table)
		{
		    $table->increments('id');
		    $table->integer('month');
		    $table->integer('year');
		    $table->integer('budget');
		    $table->integer('category');
		    $table->integer('category_id');
		    $table->timestamps();
		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('faceliftbudget');
	}

}
