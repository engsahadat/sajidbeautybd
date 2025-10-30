<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'company',
        'contact_name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'status',
        'notes'
    ];

    protected $casts = [
        'name' => 'string',
        'status' => 'string',
    ];

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'vendor_id');
    }
}
