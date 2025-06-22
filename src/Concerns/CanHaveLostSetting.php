<?php

namespace Betta\Settings\Concerns;

use Betta\Settings\Models\FqnSetting;

trait CanHaveLostSetting
{
    protected function markLost(): void
    {
        $database = FqnSetting::query()->whereNull('lost_at')->get()
            ->reject(function (FqnSetting $setting) {
                return in_array($setting->fqn, $this->settings);
            })->each(function (FqnSetting $setting) {
                $setting->markLost();
            });
    }
}
