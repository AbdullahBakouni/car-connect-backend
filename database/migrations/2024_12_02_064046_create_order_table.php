<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('date');
            $table->string('totalPrice');
            $table->foreignId('userId')->constrained('user');
            $table->foreignId('deliveryId')->nullable()->constrained('delivery');
            $table->foreignId('carId')->constrained('car');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
