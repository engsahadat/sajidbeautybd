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
        Schema::create('social_media_settings', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, instagram, twitter, linkedin, pinterest
            $table->string('app_id')->nullable();
            $table->string('app_secret')->nullable();
            $table->text('access_token')->nullable();
            $table->text('access_token_secret')->nullable(); // For Twitter OAuth 1.0
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable(); // Additional platform-specific config
            $table->timestamps();
            
            $table->unique('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_settings');
    }
};
