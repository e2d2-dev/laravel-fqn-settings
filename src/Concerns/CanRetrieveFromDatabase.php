<?php

namespace Betta\Settings\Concerns;

use Illuminate\Support\Collection;

trait CanRetrieveFromDatabase
{
    public function allFromDatabaseCollection(): Collection
    {
        return \Betta\Settings\Models\FqnSetting::all()
            ->mapWithKeys(function (\Betta\Settings\Models\FqnSetting $setting) {
                return [$setting->getSnakeFqn() => $setting->value];
            });
    }

    public function allFromDatabaseArray(): array
    {
        return $this->allFromDatabaseCollection()->toArray();
    }

    public function allFromDatabaseJson(): string
    {
        return $this->allFromDatabaseCollection()->toJson(JSON_PRETTY_PRINT);
    }
}
