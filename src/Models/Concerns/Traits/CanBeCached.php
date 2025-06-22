<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Illuminate\Support\Facades\Cache;

trait CanBeCached
{
    public function forgetCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    public function cache(): void
    {
        $this->getClassString()::get();
    }

    public function getClassString(): string
    {
        return $this->fqn;
    }

    public function getCacheKey(): string
    {
        return "settings.{$this->getSnakeFqn()}";
    }

    public function isCached(): bool
    {
        return (bool) Cache::has($this->getCacheKey());
    }
}
