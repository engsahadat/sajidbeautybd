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
        Schema::create('social_media_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_media_page_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who shared it
            $table->string('platform'); // facebook, instagram, twitter, etc.
            $table->string('post_id')->nullable(); // Platform post ID
            $table->string('post_url')->nullable(); // Direct link to post
            $table->text('message')->nullable(); // Post caption/message
            $table->json('media_urls')->nullable(); // Product images shared
            $table->string('status'); // pending, published, failed
            $table->text('error_message')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('analytics')->nullable(); // Likes, shares, comments, etc.
            $table->timestamps();
            
            $table->index(['product_id', 'platform']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_posts');
    }
};
