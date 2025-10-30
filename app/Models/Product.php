<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'slug',
        'description',
        'short_description',
        'highlight',
        'skin_concern',
        'skin_type',
        'remark',
        'country_of_origin',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'weight',
        'dimensions',
        'image',
        'gallery',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'weight' => 'decimal:2',
        'gallery' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getImageUrlAttribute(): string
    {
        $val = $this->image ?? null;
        if (is_string($val) && $val !== '') {
            if (preg_match('/^https?:\/\//i', $val)) {
                return $val;
            }
            $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
            return $base . '/' . ltrim($val, '/');
        }
        $gallery = $this->gallery;
        if (is_array($gallery)) {
            foreach ($gallery as $g) {
                if (is_string($g) && $g !== '') {
                    if (preg_match('/^https?:\/\//i', $g)) {
                        return $g;
                    }
                    $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
                    return $base . '/' . ltrim($g, '/');
                }
                if (is_array($g)) {
                    if (isset($g['path']) && is_string($g['path'])) {
                        $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
                        return $base . '/' . ltrim($g['path'], '/');
                    }
                    foreach ($g as $v) {
                        if (is_string($v) && $v !== '') {
                            $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
                            return $base . '/' . ltrim($v, '/');
                        }
                    }
                }
            }
        }

        $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
        return $base . '/images/default-image.png';
    }

    public function getMainImageUrlAttribute(): string
    {
        return $this->image_url;
    }

    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];
        $gallery = $this->gallery;
        if (is_array($gallery)) {
            $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
            foreach ($gallery as $g) {
                if (is_string($g) && $g !== '') {
                    $urls[] = preg_match('/^https?:\/\//i', $g) ? $g : ($base . '/' . ltrim($g, '/'));
                } elseif (is_array($g)) {
                    $path = $g['path'] ?? null;
                    if (is_string($path) && $path !== '') {
                        $urls[] = $base . '/' . ltrim($path, '/');
                    } else {
                        foreach ($g as $v) {
                            if (is_string($v) && $v !== '') {
                                $urls[] = $base . '/' . ltrim($v, '/');
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $urls;
    }
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getAverageRatingAttribute(): float
    {
        $reviews = $this->reviews();
        if ($reviews->count() === 0) {
            return 0;
        }
        return round($reviews->avg('rating'), 1);
    }
}
