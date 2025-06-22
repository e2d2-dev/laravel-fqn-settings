<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Illuminate\Support\Facades\Crypt;

/**
 * @property bool $encrypt
 */
trait CanBeEncrypted
{
    public function decryptValue(): mixed
    {
        return $this->encrypt ?
            Crypt::decrypt($this->value) :
            $this->value;
    }

    public function isEncrypted(): bool
    {
        return (bool) $this->encrypt;
    }
}
