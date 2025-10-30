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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('description', 500)->nullable();
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2)->unsigned();
            $table->decimal('minimum_amount', 10, 2)->unsigned()->nullable();
            $table->decimal('maximum_discount', 10, 2)->unsigned()->nullable();
            $table->integer('usage_limit')->unsigned()->nullable();
            $table->tinyInteger('usage_limit_per_customer')->unsigned()->default(1);
            $table->integer('used_count')->unsigned()->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index(['code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
