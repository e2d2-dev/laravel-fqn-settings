<?php

namespace Betta\Settings\Concerns;

use Betta\Settings\Models\FqnSetting;

trait CanHaveLostSetting
{
    protected function markLost(): array
    {
        return FqnSetting::query()->whereNull('lost_at')->get()
            ->reject(function (FqnSetting $setting) {
                return in_array($setting->fqn, $this->settings);
            })->map(function (FqnSetting $setting) {
                $setting->markLost();

                return $setting->fqn;
            })->toArray();
    }
}
