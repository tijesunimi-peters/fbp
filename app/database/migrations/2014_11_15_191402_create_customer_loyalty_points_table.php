<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLoyaltyPointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftlp', function($table)
		{
		    $table->increments('id');
		    $table->integer('client_id');
		    $table->integer('goal');
		    $table->integer('loyalty_points');
		    $table->integer('month');
		    $table->integer('year');
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
		Schema::drop('faceliftlp');
	}

}
