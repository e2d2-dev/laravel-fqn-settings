<?php

namespace Betta\Settings\Providers;

use Betta\Settings\Commands\CreateCommand;
use Betta\Settings\Commands\InstallCommand;
use Betta\Settings\Commands\SyncCommand;
use Betta\Settings\Registry;
use Betta\Settings\Settings;
use Illuminate\Support\ServiceProvider;

class FqnSettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, Registry::class);

        Settings::register();
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'fqn-settings');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../../config/fqn-settings.php', 'fqn-settings.php');

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 'laravel-fqn-settings-migrations');

        $this->publishes([
            __DIR__.'/../../config/fqn-settings.php' => config_path('fqn-settings.php'),
        ], 'laravel-fqn-settings-config');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/fqn-settings'),
        ], 'laravel-fqn-settings-translations');

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
