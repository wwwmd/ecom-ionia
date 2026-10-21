<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $s = static::where('key', $key)->first();
            return $s ? static::castValue($s->value, $s->type) : $default;
        });
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'string'): void
    {
        static::updateOrCreate(['key' => $key], [
            'value' => is_array($value) ? json_encode($value) : (string) $value,
            'group' => $group,
            'type' => $type,
        ]);
        Cache::forget("setting.$key");
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => (bool) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
