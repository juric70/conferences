<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_offers', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('code');
            $table->integer('number_of_days')->nullable();
            $table->string('description')->nullable();
            $table->integer('price');
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_offers');
    }
};
