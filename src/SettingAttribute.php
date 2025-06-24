<?php

namespace Betta\Settings;

use Betta\Settings\Models\FqnSetting;
use Illuminate\Support\Facades\Cache;

abstract class SettingAttribute
{
    public static function get()
    {
        $setting = app(static::class);

        return $setting->fromCache();
    }

    public static function set($value): void
    {
        static::query()->update(['value' => $value]);
        static::forgetCache();
    }

    public static function forgetCache(): void
    {
        Cache::forget(static::getCacheKey());
    }

    public static function query()
    {
        return FqnSetting::query()->firstWhere('fqn', static::class);
    }

    public function getSnakeKey(): string
    {
        return str(static::class)->afterLast('\\')->snake();
    }

    private function cast()
    {
        return $this->value = $this::query()?->value ?? $this->value;
    }

    private function fromCache()
    {
        return Cache::rememberForever(static::getCacheKey(), function () {
            return $this->cast();
        });
    }

    public static function getSnakeFqn(): string
    {
        return str(static::class)->remove('\\')->snake();
    }

    public function existsInDatabase(): bool
    {
        return (bool) FqnSetting::query()->where('fqn', static::class)->first();
    }

    public function recoverWhenDoesntExist(&$recovered): void
    {
        if ($this->existsInDatabase()) {
            return;
        }
        $this->recover();
        $recovered[] = static::class;
    }

    public function recover(): void
    {
        $value = $this->cast();

        FqnSetting::create([
            'key' => $this->getSnakeKey(),
            'fqn' => static::class,
            'value' => $value,
            'default' => $value,
            'type' => 'string',
        ]);
    }

    public static function getStatePath(): string
    {
        $key = str(static::class)->afterLast('\\')->snake();
        $path = str(static::class)->beforeLast('\\')->replace('\\', ' ')->snake();

        return $path.'.'.$key;
    }

    public static function getCacheKey(): string
    {
        $snake = static::getSnakeFqn();

        $config = static::getConfigCacheKey();

        return "{$config}.{$snake}";
    }

    public static function getConfigCacheKey(): string
    {
        return 'fqn_settings';
    }
}
