<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceliftAppTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftCustomers', function($table)
		{
		    $table->increments('id');
		    $table->integer('client_id');
		    $table->string('client_type');
		    $table->string('client_name');
		    $table->string('gender');
		    $table->integer('houseNo');
		    $table->string('street');
		    $table->string('location');
		    $table->string('city');
		    $table->string('religion');
		    $table->string('status');
		    $table->date('wedding_anniversary');
		    $table->date('DOB');
		    $table->string('occupation');
		    $table->string('phone_no','11');
		    $table->string('email');
		    $table->string('referee');
		    $table->string('flk');
		    $table->integer('month');
		    $table->integer('year');
		    $table->timestamps();
		
		});

		Schema::create('faceliftServices', function($table)
		{
		    $table->increments('id');
		    $table->string('service_type');
		    $table->time('service_start');
		    $table->time('service_end');
		    $table->string('attendant');
		    $table->integer('amount');
		    $table->time('arrival');
		    $table->time('departure');
		    $table->date('rebooked_date');
		    $table->integer('client_id');
		    $table->integer('staff_id');
		    $table->integer('month');
		    $table->integer('year');
		    $table->timestamps();

		
		});

		Schema::create('faceliftStaff', function($table)
		{
		    $table->increments('id');
		    $table->integer('staff_id');
		    $table->string('name');
		    $table->string('address');
		    $table->string('phone_no');
		    $table->string('email');
		    $table->timestamp('deleted_at');
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
		Schema::drop('faceliftStaff');
		Schema::drop('faceliftCustomers');
		Schema::drop('faceliftServices');
	}

}
