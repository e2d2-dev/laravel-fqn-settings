<?php

namespace Betta\Settings\Attribute;

use Illuminate\Support\Facades\Cache;

trait CanCache
{
    public static function forgetCache(): void
    {
        Cache::forget(static::getCacheKey());
    }

    private function fromCache()
    {
        return Cache::rememberForever(static::getCacheKey(), function () {
            return $this->typeCheck();
        });
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
