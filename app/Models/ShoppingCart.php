<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingCart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id')->with(['product']);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function scopeForCurrent($query)
    {
        $sessionId = session()->getId();
    $userId = Auth::id();
        return $query->when($userId, fn($q)=>$q->where('user_id',$userId))
                     ->when(!$userId, fn($q)=>$q->where('session_id',$sessionId));
    }

    public function subtotal(): float
    {
        return (float) $this->items->sum(function($i){ return (int)$i->quantity * (float)$i->unit_price; });
    }

    public function total(): float
    {
        $subtotal = $this->subtotal();
        $discount = 0.0;
        if ($this->coupon && $this->coupon->isActive()) {
            $discount = $this->coupon->discountForTotal($subtotal);
        }
        // Taxes/Shipping placeholders
        $tax = 0.0; $shipping = 0.0;
        return max(0.0, $subtotal + $tax + $shipping - $discount);
    }

    public function discount(): float
    {
        $subtotal = $this->subtotal();
        if ($this->coupon && $this->coupon->isActive()) {
            return $this->coupon->discountForTotal($subtotal);
        }
        return 0.0;
    }
}
