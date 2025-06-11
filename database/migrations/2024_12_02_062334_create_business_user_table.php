<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('business_user', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('name')->nullable();
            $table->string('desc')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->string('type')->nullable();
            $table->string('idImageUrl')->nullable();
            $table->string('commercialRegisterImageUrl')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('business_user');
    }
};
