<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetailPricesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('retail_prices', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('retailer_id');
			$table->unsignedBigInteger('product_id')->nullable();
			$table->json('prices');
			$table->json('link')->nullable();
			$table->softDeletes();
			$table->timestamps();

			$table->index('retailer_id');
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
		Schema::dropIfExists('retail_prices');
	}
}
