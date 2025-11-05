<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    protected $fillable = [
        'product_id',
        'vendor_id',
        'supplier_sku',
        'cost_price',
        'minimum_order_quantity',
        'lead_time_days',
        'is_primary'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'vendor_id' => 'integer',
        'cost_price' => 'decimal:2',
        'minimum_order_quantity' => 'integer',
        'lead_time_days' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
