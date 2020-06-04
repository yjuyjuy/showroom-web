<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->string('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('product_id');
			$table->unsignedBigInteger('seller_id');
			$table->string('seller_type');
			$table->string('size');
			$table->unsignedSmallInteger('quantity');
			$table->unsignedDecimal('price', 10, 2);
			$table->unsignedDecimal('shipping', 10, 2);
			$table->unsignedDecimal('insurance', 10, 2);
			$table->unsignedDecimal('total', 10, 2);
			$table->string('name');
			$table->string('phone');
			$table->string('address1');
			$table->string('address2');
			$table->string('city');
			$table->string('state');
			$table->string('country');
			$table->string('zip');
			$table->boolean('is_direct');
			$table->enum('status', ['created', 'confirmed', 'paid', 'shipped', 'delivered', 'completed', 'closed']);
			$table->string('tracking')->nullable();
			$table->string('reason')->nullable();
			$table->timestamp('confirmed_at')->nullable();
			$table->timestamp('paid_at')->nullable();
			$table->timestamp('shipped_at')->nullable();
			$table->timestamp('delivered_at')->nullable();
			$table->timestamp('completed_at')->nullable();
			$table->timestamp('closed_at')->nullable();
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
		Schema::dropIfExists('orders');
	}
}
