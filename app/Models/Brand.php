<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'status',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the URL for the brand logo.
     */
    public function getLogoUrlAttribute(): string
    {
        if (is_string($this->logo) && $this->logo !== '') {
            if (preg_match('/^https?:\/\//', $this->logo)) {
                return $this->logo;
            }
            return asset($this->logo);
        }
        return asset('images/default-image.png');
    }
}
