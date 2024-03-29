<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPricesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_prices', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('vendor_id');
			$table->unsignedBigInteger('product_id');
			$table->json('data');
			$table->timestamps();

			$table->index('vendor_id');
			$table->index('product_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('vendor_prices');
	}
}
