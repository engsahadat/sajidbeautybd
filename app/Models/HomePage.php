<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $table = 'home_pages';

    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        // prefer first from images array
        if (is_array($this->images) && !empty($this->images)) {
            return asset($this->images[0]);
        }
        return asset('admin_asset/images/no-image.png');
    }

    public function getImageUrlsAttribute()
    {
        $items = [];
        if (is_array($this->images)) $items = $this->images;
        elseif ($this->image) $items = [$this->image];
        return array_map(fn($p) => asset($p), $items);
    }
}
