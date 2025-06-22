<?php

namespace Betta\Settings\Models;

use Betta\Settings\Models\Concerns\Traits\CanBeCached;
use Betta\Settings\Models\Concerns\Traits\CanBeEncrypted;
use Betta\Settings\Models\Concerns\Traits\CanBeLost;
use Betta\Settings\Models\Concerns\Traits\CanCreateClasses;
use Betta\Settings\Models\Concerns\Traits\HasFqn;
use Betta\Settings\Models\Concerns\Traits\HasJsonFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * @property string $key
 * @property string $fqn
 * @property mixed $value
 * @property mixed $default
 * @property string $type
 */
class FqnSetting extends Model
{
    use CanBeCached;
    use CanBeEncrypted;
    use CanBeLost;
    use CanCreateClasses;
    use HasFqn;
    use HasJsonFallback;

    protected $table = 'fqn_settings';

    protected $casts = [
        'lost_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (FqnSetting $model) {
            $model->setAppFqnOnCreateWhenEmpty();
            $model->createClassFileOnCreateWhenFqnEmpty();
        });

        static::saving(function (FqnSetting $model) {
            $model->encryptWhenEnabled();
            $model->castStringBoolean();
        });

        static::saved(function (FqnSetting $model) {
            $model->saveFallback();
        });
    }

    public function castStringBoolean(): void
    {
        // dd($this->getAttributes());
        //        if($this->type == 'string'){
        //            dd(
        //                when($this->value, fn() => 'true', 'false')
        //            );
        //            $this->value = when($this->value, fn() => 'true', 'false');
        //            $this->default = when($this->default, fn() => 'true', 'false');
        //        }
    }

    public function encryptWhenEnabled(): mixed
    {
        return $this->value = $this->encrypt ?
            Crypt::encrypt($this->value) :
            $this->value;
    }
}
