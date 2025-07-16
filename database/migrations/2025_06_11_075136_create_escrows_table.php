<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('escrows', function (Blueprint $table) {
            $table->id('escrow_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('seller_id');
            $table->enum('status', ['pending', 'released', 'cancelled']);
            $table->timestamps();

            $table->foreign('buyer_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('business_user')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('escrows');
    }
}; 