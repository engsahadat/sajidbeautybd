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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['billing', 'shipping', 'both'])->default('both');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('company', 100)->nullable();
            $table->string('address_line_1', 191);
            $table->string('address_line_2', 191)->nullable();
            $table->string('city', 50);
            $table->string('state', 50)->nullable();
            $table->string('postal_code', 10);
            $table->string('country', 2); // ISO country code
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
