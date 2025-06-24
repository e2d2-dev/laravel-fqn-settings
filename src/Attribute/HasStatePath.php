<?php

namespace Betta\Settings\Attribute;

trait HasStatePath
{
    public static function getStatePath(): string
    {
        $key = str(static::class)->afterLast('\\')->snake();
        // $group = static::class::getGroup();
        $path = str(static::class)->beforeLast('\\')->replace('\\', '_')->snake();

        return "{$path}.{$key}";
        //        return implode('.', array_filter([
        //            $group,
        //            $path,
        //            $key,
        //        ]));
    }
}
