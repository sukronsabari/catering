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
        Schema::create('merchant_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->unique()->constrained('merchants')->onDelete('cascade');
            $table->string('address_detail');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('village');
            $table->string('postal_code');
            $table->string('country')->nullable()->default('Indonesia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_addresses');
    }
};
