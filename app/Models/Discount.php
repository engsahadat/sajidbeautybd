<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'value',
        'start_date',
        'end_date',
        'image',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        if (is_string($this->image) && $this->image !== '') {
            if (preg_match('/^https?:\/\//', $this->image)) {
                return $this->image;
            }
            return asset($this->image);
        }
        return asset('images/default-image.png');
    }
}
