<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_customer',
        'used_count',
        'starts_at',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_customer' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        if ($this->status !== 'active') return false;
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function discountForTotal(float $total): float
    {
        if ($this->minimum_amount && $total < (float)$this->minimum_amount) return 0.0;
        $discount = 0.0;
        if ($this->type === 'fixed') {
            $discount = (float) $this->value;
        } else {
            $discount = round(($total * (float)$this->value) / 100, 2);
        }
        if ($this->maximum_discount) {
            $discount = min($discount, (float)$this->maximum_discount);
        }
        return max(0.0, min($discount, $total));
    }
}
