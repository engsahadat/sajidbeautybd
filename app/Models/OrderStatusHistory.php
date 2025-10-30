<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'notify_customer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
