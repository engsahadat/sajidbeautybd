<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    // Display style constants
    const STYLE_RECTANGLE = 'rectangle';
    const STYLE_CIRCLE = 'circle';
    const STYLE_IMAGE = 'image';
    const STYLE_COLOR = 'color';
    const STYLE_RADIO = 'radio';
    const STYLE_DROPDOWN = 'dropdown';

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'sku',
        'price',
        'stock_quantity',
        'is_default',
        'image',
        'sort_order',
        'display_style',
        'color_code',
        'swatch_image',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get available display styles
     */
    public static function getDisplayStyles(): array
    {
        return [
            self::STYLE_RECTANGLE => 'Rectangle Buttons',
            self::STYLE_CIRCLE => 'Circle Buttons',
            self::STYLE_IMAGE => 'Image Swatch',
            self::STYLE_COLOR => 'Color Swatch',
            self::STYLE_RADIO => 'Radio Buttons',
            self::STYLE_DROPDOWN => 'Dropdown Select',
        ];
    }

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the effective price (variant price if set, otherwise product price)
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->price && $this->price > 0) {
            return (float) $this->price;
        }
        return (float) $this->product->effective_price;
    }

    /**
     * Get variant display name (e.g., "Size: M", "Color: Red")
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ': ' . $this->value;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && is_string($this->image) && $this->image !== '') {
            if (preg_match('/^https?:\/\//i', $this->image)) {
                return $this->image;
            }
            $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
            return $base . '/' . ltrim($this->image, '/');
        }
        // Fallback to product image
        return $this->product->image_url ?? '';
    }

    /**
     * Get swatch image URL
     */
    public function getSwatchImageUrlAttribute(): ?string
    {
        if ($this->swatch_image && is_string($this->swatch_image) && $this->swatch_image !== '') {
            if (preg_match('/^https?:\/\//i', $this->swatch_image)) {
                return $this->swatch_image;
            }
            $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
            return $base . '/' . ltrim($this->swatch_image, '/');
        }
        return null;
    }
}
