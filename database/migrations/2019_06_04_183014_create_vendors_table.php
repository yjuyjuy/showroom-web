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
			$table->string('name');
			$table->string('wechat_id')->nullable();
			$table->unsignedBigInteger('retailer_id')->nullable();
			$table->float('min_profit_rate')->default(10.0);
			$table->string('city');
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
		Schema::dropIfExists('vendors');
	}
}
