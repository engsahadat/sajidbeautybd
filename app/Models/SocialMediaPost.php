<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaPost extends Model
{
    protected $fillable = [
        'social_media_page_id',
        'product_id',
        'user_id',
        'platform',
        'post_id',
        'post_url',
        'message',
        'media_urls',
        'status',
        'error_message',
        'published_at',
        'analytics'
    ];

    protected $casts = [
        'media_urls' => 'array',
        'analytics' => 'array',
        'published_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_FAILED = 'failed';

    /**
     * Get the page this post belongs to
     */
    public function page()
    {
        return $this->belongsTo(SocialMediaPage::class, 'social_media_page_id');
    }

    /**
     * Get the product that was shared
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who shared the post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope for failed posts
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for pending posts
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
