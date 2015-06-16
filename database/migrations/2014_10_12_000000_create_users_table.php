<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('role_id')->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
            $table->string('first_name');
            $table->string('last_name');
			$table->rememberToken();
			$table->timestamps();
            $table->softDeletes();


		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
