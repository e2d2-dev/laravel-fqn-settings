<?php

namespace Betta\Settings;

use Betta\Settings\Collections\SyncLogCollection;
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

    public array $settings = [];

    public array $synced = [];

    public function sync(): SyncLogCollection
    {
        foreach ($this->settings as $setting) {
            app($setting)->recoverWhenDoesntExist($this->synced);
        }

        $lost = $this->markLost();

        return SyncLogCollection::make()
            ->addSynced($this->synced)
            ->addLost($lost);
    }

    public function getDiscoveredSettings(): array
    {
        return $this->settings;
    }
}
