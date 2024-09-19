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
        Schema::create('product_attribute_sku', function (Blueprint $table) {
            $table->primary(['product_attribute_id', 'sku_id']);
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->foreignId('sku_id')->constrained('skus')->cascadeOnDelete();
            $table->string('value')->comment('product_attributes and skus combination. E.g: White - L');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_sku');
    }
};
