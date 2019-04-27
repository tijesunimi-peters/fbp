<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceliftCommissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftstaffcommission', function($table){
			$table->increments('id');
			$table->integer('commission');
			$table->integer('staff_id');
			$table->integer('customer_id');
			$table->string('services');
			$table->integer('amount');
			$table->date('date');
			$table->integer('month');
			$table->integer('year');
			$table->timestamps();
		});

		Schema::create('faceliftreferrals', function($table)
		{
		    $table->increments('id');
		    $table->integer('client_id');
		    $table->string('refered');
		    $table->timestamps();
		    $table->integer('staff_id');
		    $table->integer('customer_id');
		    $table->integer('month');
		    $table->integer('year');
		    
		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('faceliftstaffcommission');
		Schema::drop('faceliftreferrals');
	}

}
