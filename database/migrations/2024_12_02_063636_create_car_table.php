<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('car', function (Blueprint $table) {
            $table->id();
            $table->string('desc');
            $table->string('price');
            $table->integer('available')->default(1);  // 0 no 1 yes
            $table->string('killo');
            $table->string('ownerShipImageUrl')->nullable();
            $table->foreignId('colorId')->constrained('color');
            $table->foreignId('gearId')->constrained('gear');
            $table->foreignId('brandId')->constrained('brand');
            $table->foreignId('modelId')->constrained('model');
            $table->foreignId('userId')->constrained('business_user');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('car');
    }
};
