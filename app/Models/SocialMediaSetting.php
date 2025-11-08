<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaSetting extends Model
{
    protected $fillable = [
        'platform',
        'app_id',
        'app_secret',
        'access_token',
        'access_token_secret',
        'token_expires_at',
        'is_active',
        'config'
    ];

    protected $casts = [
        'config' => 'array',
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'app_secret',
        'access_token',
        'access_token_secret'
    ];

    // Platform constants
    const PLATFORM_FACEBOOK = 'facebook';
    const PLATFORM_INSTAGRAM = 'instagram';
    const PLATFORM_TWITTER = 'twitter';
    const PLATFORM_LINKEDIN = 'linkedin';
    const PLATFORM_PINTEREST = 'pinterest';

    public static function getPlatforms()
    {
        return [
            self::PLATFORM_FACEBOOK => 'Facebook',
            self::PLATFORM_INSTAGRAM => 'Instagram',
            self::PLATFORM_TWITTER => 'Twitter',
            self::PLATFORM_LINKEDIN => 'LinkedIn',
            self::PLATFORM_PINTEREST => 'Pinterest',
        ];
    }

    /**
     * Get all connected pages for this platform
     */
    public function pages()
    {
        return $this->hasMany(SocialMediaPage::class);
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired()
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return $this->token_expires_at->isPast();
    }
}
