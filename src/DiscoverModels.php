<?php

namespace Betta\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function Filament\Support\discover_app_classes;

class DiscoverModels
{
    public static function toCollection(): Collection
    {
        return collect(discover_app_classes(Model::class))->mapWithKeys(function ($class) {
            return [$class => class_basename($class)];
        });
    }
}
