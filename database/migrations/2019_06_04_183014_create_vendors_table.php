<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendors', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('user_id');
			$table->string('name');
			$table->string('wechat_id')->nullable();
			$table->string('city');
			$table->timestamps();

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
		Schema::dropIfExists('vendors');
	}
}
