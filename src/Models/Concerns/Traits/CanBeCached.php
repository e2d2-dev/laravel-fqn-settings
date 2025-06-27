<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Betta\Settings\SettingAttribute;
use Illuminate\Support\Facades\Cache;

trait CanBeCached
{
    public function forgetCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    public function cache(): void
    {
        if ($this->classFileExists()) {
            $this->getClassString()::get();
        } else {
            $this->toCache();
        }
    }

    private function toCache(): void
    {
        Cache::rememberForever(static::getCacheKey(), function () {
            return $this->value;
        });
    }

    public function getClassString(): string
    {
        return $this->fqn;
    }

    public function classFileExists(): bool
    {
        return class_exists($this->fqn);
    }

    public function getCacheKey(): string
    {
        $config = SettingAttribute::getConfigCacheKey();

        return "{$config}.{$this->getSnakeFqn()}";
    }

    public function isCached(): bool
    {
        return (bool) Cache::has($this->getCacheKey());
    }

    public function cacheDiffers(): bool
    {
        return $this->isCached() and $this->value !== Cache::get($this->getCacheKey());
    }
}
