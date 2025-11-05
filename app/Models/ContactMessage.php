<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'meta',
        'responded_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'responded_at' => 'datetime',
    ];
}
