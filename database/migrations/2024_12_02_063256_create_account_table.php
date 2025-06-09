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
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('accountNumber')->unique();
            $table->foreignId('userId')->nullable()->constrained('user');
            $table->foreignId('businessUserId')->nullable()->constrained('business_user');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
