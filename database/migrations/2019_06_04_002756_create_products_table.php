<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('designer_style_id')->nullable();
			$table->string('name');
			$table->string('name_cn');
			$table->unsignedBigInteger('category_id');
			$table->unsignedBigInteger('season_id');
			$table->unsignedBigInteger('color_id');
			$table->unsignedBigInteger('brand_id');
			$table->unsignedBigInteger('taobao_id')->nullable();
			$table->text('comment')->nullable();
			$table->timestamps();

			$table->index('category_id');
			$table->index('season_id');
			$table->index('color_id');
			$table->index('brand_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('products');
	}
}
