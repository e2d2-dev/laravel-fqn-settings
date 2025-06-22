<?php

namespace Betta\Settings\Models\Concerns\Traits;

use Illuminate\Support\Carbon;

/**
 * @property ?Carbon $lost_at
 */
trait CanBeLost
{
    public function markLost(): void
    {
        $this->lost_at = now();
        $this->save();
    }

    public function isLost(): bool
    {
        return (bool) $this->lost_at;
    }

    public function resetLost(): void
    {
        $this->update(['lost_at' => null]);
    }
}
