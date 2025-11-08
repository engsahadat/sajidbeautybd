<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaPage extends Model
{
    protected $fillable = [
        'social_media_setting_id',
        'platform',
        'page_id',
        'page_name',
        'page_username',
        'page_url',
        'page_access_token',
        'page_picture',
        'is_connected',
        'connected_at',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_connected' => 'boolean',
        'connected_at' => 'datetime'
    ];

    protected $hidden = [
        'page_access_token'
    ];

    /**
     * Get the social media setting
     */
    public function setting()
    {
        return $this->belongsTo(SocialMediaSetting::class, 'social_media_setting_id');
    }

    /**
     * Get all posts from this page
     */
    public function posts()
    {
        return $this->hasMany(SocialMediaPost::class);
    }

    /**
     * Get platform icon
     */
    public function getPlatformIconAttribute()
    {
        $icons = [
            'facebook' => 'fa-facebook',
            'instagram' => 'fa-instagram',
            'twitter' => 'fa-twitter',
            'linkedin' => 'fa-linkedin',
            'pinterest' => 'fa-pinterest',
        ];

        return $icons[$this->platform] ?? 'fa-share-alt';
    }

    /**
     * Get platform color
     */
    public function getPlatformColorAttribute()
    {
        $colors = [
            'facebook' => '#1877f2',
            'instagram' => '#e4405f',
            'twitter' => '#1da1f2',
            'linkedin' => '#0a66c2',
            'pinterest' => '#e60023',
        ];

        return $colors[$this->platform] ?? '#6c757d';
    }
}
