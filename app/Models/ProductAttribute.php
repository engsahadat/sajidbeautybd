<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_name',
        'attribute_value',
        'attribute_group',
        'sort_order',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the attribute
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
