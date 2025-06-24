<?php

namespace Betta\Settings\Models\Concerns\Traits;

trait HasFqn
{
    public function setAppFqnOnCreateWhenEmpty(): void
    {
        $appFqn = 'App\\Settings\\';

        if ($this->fqn === null) {
            $this->fqn = "{$appFqn}{$this->getStudlyKey()}";
        }
    }

    public function getStudlyKey(): string
    {
        return str($this->key)->studly();
    }

    public function getFqn(): string
    {
        $class = $this->fqn;

        if (class_exists($class)) {
            return $class;
        }
        throw new \Exception('Setting class does not exist.');
    }

    public function getSnakeFqn(): string
    {
        return str($this->fqn)->remove('\\')->snake();
    }

    public function getFqnEnd(): string
    {
        return str($this->getFqn())->afterLast('\\');
    }
}
