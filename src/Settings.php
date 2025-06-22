<?php

namespace Betta\Settings;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Registry::class;
    }
}
