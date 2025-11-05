<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key_name', 'value', 'type', 'description', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Accessor to return appropriately cast value
    public function getCastedValueAttribute()
    {
        $type = $this->type ?: 'string';
        $raw = $this->value;
        try {
            return match ($type) {
                'integer' => (int) $raw,
                'boolean' => filter_var($raw, FILTER_VALIDATE_BOOLEAN),
                'json'    => $raw ? json_decode($raw, true) : null,
                default   => (string) ($raw ?? ''),
            };
        } catch (\Throwable $e) {
            return $raw;
        }
    }

    public static function get(string $key, $default = null)
    {
        $row = static::query()->where('key_name', $key)->first();
        return $row ? $row->casted_value : $default;
    }

    public static function getMany(array $keys): array
    {
        return static::query()->whereIn('key_name', $keys)->get()
            ->keyBy('key_name')
            ->map(fn($r) => $r->casted_value)
            ->all();
    }

    public static function set(string $key, $value, string $type = 'string', ?string $description = null, bool $isPublic = false): Setting
    {
        $val = $value;
        if ($type === 'json') {
            $val = $value !== null ? json_encode($value) : null;
        } elseif ($type === 'boolean') {
            $val = $value ? '1' : '0';
        }

        return static::updateOrCreate(
            ['key_name' => $key],
            [
                'value' => $val,
                'type' => $type,
                'description' => $description,
                'is_public' => $isPublic,
            ]
        );
    }
}
