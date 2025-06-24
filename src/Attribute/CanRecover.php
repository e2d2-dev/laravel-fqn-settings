<?php

namespace Betta\Settings\Attribute;

use Betta\Settings\Models\FqnSetting;

trait CanRecover
{
    public function recoverWhenDoesntExist(&$recovered): void
    {
        if ($this->existsInDatabase()) {
            return;
        }
        $this->recover();
        $recovered[] = static::class;
    }

    public function recover(): void
    {
        $value = $this->typeCheck();

        $reflection = new \ReflectionClass(static::class);

        $type = $reflection->getProperty('value')->getType();
        $nullable = $type->allowsNull();

        FqnSetting::create([
            'key' => $this->getSnakeKey(),
            'fqn' => static::class,
            'value' => $value,
            'default' => $value,
            'type' => $type->getName(),
            'nullable' => $nullable,
        ]);
    }
}
