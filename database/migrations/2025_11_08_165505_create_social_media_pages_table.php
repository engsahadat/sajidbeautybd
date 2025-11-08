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
        Schema::create('social_media_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_media_setting_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // facebook, instagram, twitter, etc.
            $table->string('page_id'); // Platform-specific page/account ID
            $table->string('page_name');
            $table->string('page_username')->nullable();
            $table->string('page_url')->nullable();
            $table->text('page_access_token')->nullable(); // Page-specific token
            $table->string('page_picture')->nullable();
            $table->boolean('is_connected')->default(true);
            $table->timestamp('connected_at')->nullable();
            $table->json('metadata')->nullable(); // Additional page info
            $table->timestamps();
            
            $table->unique(['platform', 'page_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_pages');
    }
};
