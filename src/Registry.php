<?php

namespace Betta\Settings;

use Betta\Settings\Concerns\CanDiscoverPaths;
use Betta\Settings\Concerns\CanDiscoverSettings;
use Betta\Settings\Concerns\CanHaveLostSetting;
use Betta\Settings\Concerns\CanRetrieveFromDatabase;

class Registry
{
    use CanDiscoverPaths;
    use CanDiscoverSettings;
    use CanHaveLostSetting;
    use CanRetrieveFromDatabase;

    protected array $settings = [];

    protected array $recovered = [];

    public function __construct()
    {
        $this->addPaths();
    }

    public static function run(): void
    {
        app(static::class)->execute();
    }

    public function recover(): array
    {
        foreach ($this->settings as $setting) {
            app($setting)->recoverWhenDoesntExist($this->recovered);
        }

        $this->markLost();

        return [$this->recovered, count($this->recovered)];
    }

    public function getDiscoveredSettings(): array
    {
        return $this->settings;
    }
}
