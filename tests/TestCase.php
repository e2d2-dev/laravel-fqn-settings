<?php

namespace Betta\Settings\Tests;

use Betta\Settings\Providers\FqnSettingsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            FqnSettingsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set a dummy application key for encryption tests
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Set up the fqn-settings configuration
        $app['config']->set('fqn-settings.cache.enabled', true);
        $app['config']->set('fqn-settings.fallback.enabled', false);
        $app['config']->set('fqn-settings.fallback.file', $app->configPath('setting-fallback.json'));
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run the migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }
}
