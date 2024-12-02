<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('account', function (Blueprint $table) {
            $table->id();
            $table->string('balance');
            $table->double('accountNumber');
            $table->foreignId('userId')->constrained('user');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
