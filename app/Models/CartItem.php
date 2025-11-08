<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'variant_details',
        'quantity',
        'unit_price',
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(ShoppingCart::class, 'cart_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getLineTotalAttribute(): float
    {
        $qty = (int)($this->quantity ?? 0);
        $price = (float)($this->unit_price ?? 0);
        return $qty * $price;
    }

    /**
     * Get variant display text
     */
    public function getVariantDisplayAttribute(): ?string
    {
        if ($this->variant_id && $this->variant) {
            return $this->variant->display_name;
        }
        if ($this->variant_details) {
            $details = json_decode($this->variant_details, true);
            if (is_array($details) && isset($details['name']) && isset($details['value'])) {
                return $details['name'] . ': ' . $details['value'];
            }
        }
        return null;
    }
}
