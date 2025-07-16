<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('from_account_id');
            $table->unsignedBigInteger('to_account_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled']);
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('from_account_id')->references('id')->on('account')->onDelete('cascade');
            $table->foreign('to_account_id')->references('id')->on('account')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('order')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 