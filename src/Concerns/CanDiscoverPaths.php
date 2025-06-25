<?php

namespace Betta\Settings\Concerns;

use Betta\Settings\SettingAttribute;

trait CanDiscoverPaths
{
    public array $directories = [];

    public function register(): void
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
        $config = config('fqn-settings.discover') ?? [];

        foreach ($config as $path => $namespace) {
            $this->path($path, $namespace);
        }
    }

    public function path(string $in, string $for): void
    {
        $path = base_path(
            trim($in, '/')
        );

        $in = trim($for, '\\');

        dump($in, $for);
        $this->directories[$path] = $in;
        $this->discoverSettings(SettingAttribute::class, $this->settings, $path, $for);
    }

    public function getDiscoveredPaths(): array
    {
        return $this->directories;
    }
}
