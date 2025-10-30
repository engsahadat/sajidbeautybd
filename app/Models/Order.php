<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'currency',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'shipping_method',
        'notes',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'billing_phone',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'shipping_phone',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function recalcTotals(): void
    {
        $subtotal = (float) $this->items()->sum('total_price');
        $tax = (float) ($this->tax_amount ?? 0);
        $shipping = (float) ($this->shipping_amount ?? 0);
        $discount = (float) ($this->discount_amount ?? 0);
        $total = $subtotal + $tax + $shipping - $discount;
        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $total,
        ]);
    }

    public function paidAmount(): float
    {
        $completed = (float) $this->payments()->where('status', 'completed')->sum('amount');
        $refunded = (float) $this->payments()->where('status', 'refunded')->sum('amount');
        return max(0.0, $completed - $refunded);
    }

    public function refreshPaymentStatus(): void
    {
        $total = (float) ($this->total_amount ?? 0);
        $completed = (float) $this->payments()->where('status', 'completed')->sum('amount');
        $refunded = (float) $this->payments()->where('status', 'refunded')->sum('amount');
        $net = max(0.0, $completed - $refunded);

        $new = 'pending';
        if ($net >= $total && $total > 0) {
            $new = 'paid';
        } elseif ($refunded > 0) {
            $new = ($refunded >= $completed && $completed > 0) ? 'refunded' : 'partially_refunded';
        }
        if ($new === 'pending') {
            $hasFailed = $this->payments()->where('status', 'failed')->exists();
            if ($hasFailed && $completed == 0.0) {
                $new = 'failed';
            }
        }
        if ($new !== $this->payment_status) {
            $this->update(['payment_status' => $new]);
        }
    }
}
