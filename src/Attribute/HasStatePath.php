<?php

namespace Betta\Settings\Attribute;

trait HasStatePath
{
    public static function getStatePath(): string
    {
        $key = str(static::class)->afterLast('\\')->snake();
        $path = str(static::class)->beforeLast('\\')->replace('\\', ' ')->snake();

        return $path.'.'.$key;
    }
}
