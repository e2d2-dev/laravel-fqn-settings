<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Illuminate\Support\Facades\Artisan;

trait CanCreateClasses
{
    public function createClassFileOnCreateWhenFqnEmpty(): void
    {
        if (! empty($this->fqn)) {
            return;
        }
        when(app()->environment('local'), function () {
            $this->createClassFile();
        });
    }

    public function createClassFile(): void
    {
        Artisan::call("make:setting --name={$this->key} --value={$this->encryptWhenEnabled()} --type={$this->type}");
    }
}
