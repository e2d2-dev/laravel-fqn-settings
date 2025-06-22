<?php

namespace Betta\Settings\Providers;

use Betta\Settings\Commands\CreateCommand;
use Betta\Settings\Commands\InstallCommand;
use Betta\Settings\Commands\SyncCommand;
use Betta\Settings\Registry;
use Betta\Settings\Settings;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, Registry::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'fqn-settings');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../../config/fqn-settings.php', 'fqn-settings');

        $this->bootPackageCommands();
    }

    protected function bootPackageCommands(): void
    {
        $this->commands([
            InstallCommand::class,
            CreateCommand::class,
            SyncCommand::class,
        ]);
    }
}
