<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('wechat_id')->unique()->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->unsignedBigInteger('vendor_id')->nullable();
			$table->string('type')->nullable();
			$table->rememberToken();
			$table->timestamps();

			$table->index('username');
			$table->index('email');
			$table->index('vendor_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
