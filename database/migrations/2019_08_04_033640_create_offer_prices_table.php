<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferPricesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('offer_prices', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('vendor_id');
			$table->unsignedBigInteger('product_id');
			$table->json('prices');
			$table->softDeletes();
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
		Schema::dropIfExists('offer_prices');
	}
}
