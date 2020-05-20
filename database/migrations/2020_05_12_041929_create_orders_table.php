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
            $table->unsignedBigInteger('vendor_id');
            $table->string('size');
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
						$table->timestamp('confirmed_at');
						$table->timestamp('paid_at');
						$table->timestamp('shipped_at');
						$table->timestamp('delivered_at');
						$table->timestamp('completed_at');
						$table->timestamp('closed_at');
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
