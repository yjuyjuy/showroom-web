<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function (Blueprint $table) {
			$table->string('token');
			$table->unsignedBigInteger('user_id');
			$table->enum('os', ['ios', 'android']);
			$table->enum('app', ['com.yjuyjuy.showroomseller', 'com.yjuyjuy.showroomcustomer']);
			$table->timestamps();
			$table->primary('token');

			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('devices');
	}
}
