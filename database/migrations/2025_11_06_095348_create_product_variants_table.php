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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name', 100); // e.g., "Size", "Color", "Material"
            $table->string('value', 100); // e.g., "M", "Red", "Cotton"
            $table->string('sku', 50)->unique()->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Override price if different
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_default')->default(false);
            $table->string('image')->nullable(); // Variant-specific image
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'is_default']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
