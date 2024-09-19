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
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable()->comment('The actual alpha-numeric SKU code');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->char('currency_code', 3)->default('IDR');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('weight', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
