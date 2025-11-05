<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'sort_order',
        'meta_title',
        'meta_description',
        'is_active',
        'status'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the URL for the category image.
     */
    public function getImageUrlAttribute()
    {
        if (is_string($this->image) && $this->image !== '') {
            if (preg_match('/^https?:\/\//', $this->image)) {
                return $this->image;
            }
            return asset($this->image);
        }
        return asset('images/default-image.png');
    }

    /**
     * A Category has many Products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
