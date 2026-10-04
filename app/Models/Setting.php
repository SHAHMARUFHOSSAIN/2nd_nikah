<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            Cache::forget("setting.{$setting->key}");
        });

        static::deleted(function (Setting $setting) {
            Cache::forget("setting.{$setting->key}");
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::rememberForever("setting.{$key}", function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $setting->value,
            'float' => (float) $setting->value,
            'json', 'array' => json_decode($setting->value, true) ?? [],
            default => $setting->value,
        };
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', ?string $description = null): static
    {
        $stringValue = match ($type) {
            'boolean', 'bool' => $value ? '1' : '0',
            'json', 'array' => is_string($value) ? $value : json_encode($value),
            default => (string) ($value ?? ''),
        };

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $stringValue,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Get full public asset URL for a stored setting file path.
     * Verifies physical file existence before returning the URL to prevent broken <img> tags.
     */
    public static function getAssetUrl(string $key, ?string $default = null): ?string
    {
        $path = static::get($key);

        if (blank($path)) {
            return $default;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim(preg_replace('#^storage/#', '', ltrim((string) $path, '/')), '/');

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        return $default;
    }

    /**
     * Alias for getAssetUrl for backward compatibility.
     */
    public static function getUrl(string $key, ?string $default = null): ?string
    {
        return static::getAssetUrl($key, $default);
    }
}
