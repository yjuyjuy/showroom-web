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
			$table->unsignedBigInteger('product_id');
			$table->json('prices');
			$table->text('link')->nullable();
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
