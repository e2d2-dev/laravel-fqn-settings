<?php

namespace Betta\Settings;

use Betta\Settings\Attribute\CanCache;
use Betta\Settings\Attribute\CanRecover;
use Betta\Settings\Attribute\CanSnake;
use Betta\Settings\Attribute\HasDatabase;
use Betta\Settings\Attribute\HasStatePath;
use Betta\Settings\Models\FqnSetting;
use Illuminate\Support\Facades\Cache;

abstract class SettingAttribute
{
    use CanCache;
    use CanRecover;
    use CanSnake;
    use HasDatabase;
    use HasStatePath;

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

    private function cast()
    {
        return $this->value = $this::query()?->value ?? $this->value;
    }
}
