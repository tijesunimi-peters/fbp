<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceliftusersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftusers', function($table)
		{
		    $table->increments('id');
		    $table->string('username');
		    $table->string('password',60);
		    $table->boolean('admin');
		    $table->boolean('super_admin');
		    $table->string('remember_token');
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
		Schema::drop('faceliftusers');
	}

}
