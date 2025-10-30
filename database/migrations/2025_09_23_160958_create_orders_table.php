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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->char('currency', 3)->default('USD'); // ISO currency code
            $table->decimal('subtotal', 10, 2)->unsigned();
            $table->decimal('tax_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('shipping_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('discount_amount', 10, 2)->unsigned()->default(0);
            $table->decimal('total_amount', 10, 2)->unsigned();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->string('payment_method', 30)->nullable();
            $table->string('shipping_method', 50)->nullable();
            $table->text('notes')->nullable();
            
            // Billing address
            $table->string('billing_first_name', 50);
            $table->string('billing_last_name', 50);
            $table->string('billing_company', 100)->nullable();
            $table->string('billing_address_line_1', 191);
            $table->string('billing_address_line_2', 191)->nullable();
            $table->string('billing_city', 50);
            $table->string('billing_state', 50)->nullable();
            $table->string('billing_postal_code', 10);
            $table->string('billing_country', 2); // ISO country code
            $table->string('billing_phone', 20)->nullable();
            
            // Shipping address
            $table->string('shipping_first_name', 50);
            $table->string('shipping_last_name', 50);
            $table->string('shipping_company', 100)->nullable();
            $table->string('shipping_address_line_1', 191);
            $table->string('shipping_address_line_2', 191)->nullable();
            $table->string('shipping_city', 50);
            $table->string('shipping_state', 50)->nullable();
            $table->string('shipping_postal_code', 10);
            $table->string('shipping_country', 2); // ISO country code
            $table->string('shipping_phone', 20)->nullable();
            
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
