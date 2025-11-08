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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('attribute_name', 100); // e.g., "Material", "Weight", "Ingredients"
            $table->text('attribute_value'); // e.g., "Cotton", "250g", "Natural extracts"
            $table->string('attribute_group', 50)->nullable(); // e.g., "Technical Specs", "Features"
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'attribute_group']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
