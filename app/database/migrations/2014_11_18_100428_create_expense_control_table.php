<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseControlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faceliftcontrol', function($table) 
		{
			$table->increments('id');
			$table->string('type');
			$table->string('particulars');
			$table->integer('amount');
			$table->string('staff');
			$table->string('approval');
			$table->integer('budgetary_provision');
			$table->integer('cummulative_figure');
			$table->integer('cummulative_balance');
			$table->string('user_unit');
			$table->integer('month');
			$table->integer('year');
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
		Schema::drop('faceliftcontrol');
	}

}
