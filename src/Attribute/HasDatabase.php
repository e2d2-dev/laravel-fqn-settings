<?php

namespace Betta\Settings\Attribute;

use Betta\Settings\Models\FqnSetting;

trait HasDatabase
{
    public static function query()
    {
        return FqnSetting::query()->firstWhere('fqn', static::class);
    }
    public function existsInDatabase(): bool
    {
        return (bool) FqnSetting::query()->where('fqn', static::class)->first();
    }
}
