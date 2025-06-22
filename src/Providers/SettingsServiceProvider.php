<?php

namespace Betta\Settings\Providers;

use Betta\Settings\Commands\CreateCommand;
use Betta\Settings\Commands\RecoverCommand;
use Betta\Settings\Registry;
use Betta\Settings\Settings;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, Registry::class);

        Settings::path('app-modules/fqn-settings/src/Settings', 'Betta\\Settings\\Settings');
    }

    public function boot(): void
    {

        $this->bootPackageCommands();
        // when($this->app->runningInConsole(), fn() => dd());
    }

    protected function bootPackageCommands(): void
    {
        $this->commands([
            CreateCommand::class,
            RecoverCommand::class,
        ]);
    }
}
