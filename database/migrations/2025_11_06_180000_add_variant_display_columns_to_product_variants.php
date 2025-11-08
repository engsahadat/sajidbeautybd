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
        Schema::table('product_variants', function (Blueprint $table) {
            // Display style: rectangle, circle, image, color, radio, dropdown
            $table->string('display_style', 20)->default('rectangle')->after('sort_order');
            
            // For color variants: hex color code
            $table->string('color_code', 7)->nullable()->after('display_style');
            
            // For image swatch variants: path to swatch image
            $table->string('swatch_image')->nullable()->after('color_code');
            
            $table->index('display_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['display_style', 'color_code', 'swatch_image']);
        });
    }
};
