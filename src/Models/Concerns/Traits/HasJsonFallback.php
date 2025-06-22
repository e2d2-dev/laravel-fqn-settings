<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Betta\Settings\Settings;

trait HasJsonFallback
{
    public function saveFallback(): void
    {
        if(! config('fqn-settings.fallback.enabled')) {
            return;
        }
        $file = config_path('setting-fallback.json');

        file_put_contents($file, Settings::allFromDatabaseJson());
    }
}
