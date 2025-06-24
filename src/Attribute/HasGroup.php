<?php

namespace Betta\Settings\Attribute;

trait HasGroup
{
    protected static string|null|\BackedEnum $group;

    public static function getGroup(): ?string
    {
        return static::$group ?? null;
    }
}
