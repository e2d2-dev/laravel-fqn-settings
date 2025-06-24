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
        $value = $this->cast();

        $reflection = new \ReflectionClass(static::class);

        FqnSetting::create([
            'key' => $this->getSnakeKey(),
            'fqn' => static::class,
            'value' => $value,
            'default' => $value,
            'type' => $reflection->getProperty('value')->getType(),
        ]);
    }
}
