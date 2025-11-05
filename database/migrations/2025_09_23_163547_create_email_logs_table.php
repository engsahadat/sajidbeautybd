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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_email', 191);
            $table->string('subject', 191);
            $table->enum('type', ['order_confirmation', 'shipping_update', 'promotional', 'password_reset', 'welcome']);
            $table->enum('status', ['sent', 'failed', 'delivered', 'bounced', 'opened', 'clicked'])->default('sent');
            $table->string('provider', 30)->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            
            $table->index(['recipient_email', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
