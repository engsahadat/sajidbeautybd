<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'variant_details',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
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
