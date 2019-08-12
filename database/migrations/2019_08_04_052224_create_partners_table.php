<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partners', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('vendor_id');
			$table->unsignedBigInteger('retailer_id');
			$table->float('profit_rate')->default(15.0);
			$table->timestamps();

			$table->index('vendor_id');
			$table->index('retailer_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('partners');
	}
}
