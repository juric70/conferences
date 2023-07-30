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
        Schema::create('offer_organizations', function (Blueprint $table) {
            $table->id();
            $table->boolean('paid');
            $table->date('payment_date')->nullable();
            $table->foreignId('organizations_offer_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_organizations');
    }
};
