<?php

namespace Betta\Settings\Concerns;

use Betta\Settings\SettingAttribute;

trait CanDiscoverPaths
{
    protected array $directories = [];

    protected function addPaths(): void
    {
        $this->addConfigPaths();
        $this->addAppPath();
    }

    public function addAppPath(): void
    {
        $this->path('app/Settings', 'App\\Settings');
    }

    public function addConfigPaths(): void
    {
        $config = config('setting.discover');

        foreach ($config as $path => $namespace) {
            $this->path($path, $namespace);
        }
    }

    public function path(string $in, string $for): void
    {
        $path = base_path($in);
        $this->directories[$path] = $for;
        $this->discoverSettings(SettingAttribute::class, $this->settings, $path, $for);
    }

    public function getDiscoveredPaths(): array
    {
        return $this->directories;
    }
}
