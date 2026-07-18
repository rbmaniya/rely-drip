<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allSettings()->get($key, $default);
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('website_settings');
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('website_settings');
    }

    public static function allSettings(): Collection
    {
        // Cache as a plain array (not an Eloquent/Support Collection instance) since
        // Laravel's cache stores only unserialize an allow-listed set of classes.
        $values = Cache::rememberForever('website_settings', fn () => static::query()->pluck('value', 'key')->toArray());

        return collect($values);
    }
}
