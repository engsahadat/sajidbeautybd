<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'gateway',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'processed_at',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function markCompleted(?string $transactionId = null): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'processed_at' => now(),
        ]);
        $this->order?->refreshPaymentStatus();
    }

    public function markRefunded(float $amount): void
    {
        $this->update([
            'status' => 'refunded',
            'amount' => $amount,
            'processed_at' => now(),
        ]);
        $this->order?->refreshPaymentStatus();
    }
}
