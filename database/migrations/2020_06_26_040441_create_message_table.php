<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('sender_type');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('recipient_type');
            $table->string('content', 510);
            $table->timestamps();

            $table->index('sender_id');
            $table->index('sender_type');
            $table->index('recipient_id');
            $table->index('recipient_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
