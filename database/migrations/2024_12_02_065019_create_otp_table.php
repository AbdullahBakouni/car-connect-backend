<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('otp', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('userId')->nullable()->constrained('user');
            $table->foreignId('deliveryId')->nullable()->constrained('delivery');
            $table->foreignId('businessAccountId')->nullable()->constrained('business_user');

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};
