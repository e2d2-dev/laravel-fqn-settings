<?php

namespace Betta\Settings\Attribute;

trait CanSnake
{
    public function getSnakeKey(): string
    {
        return str(static::class)->afterLast('\\')->snake();
    }

    public static function getSnakeFqn(): string
    {
        return str(static::class)->remove('\\')->snake();
    }
}
