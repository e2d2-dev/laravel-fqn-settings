<?php

use Betta\Settings\Providers\FqnSettingsServiceProvider;
use Betta\Settings\Registry;
use Betta\Settings\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

uses(\Betta\Settings\Tests\TestCase::class, RefreshDatabase::class);

test('it registers singleton', function () {
    $registry = $this->app->make(Settings::class);
    expect($registry)->toBeInstanceOf(Registry::class);

    // Test that it's a singleton by comparing instances
    $registry2 = $this->app->make(Settings::class);
    expect($registry)->toBe($registry2);
});

test('it registers commands', function () {
    // Get all registered commands
    $commands = Artisan::all();

    // Check if our commands are registered
    expect($commands)->toHaveKey('settings:install');
    expect($commands)->toHaveKey('make:setting');
    expect($commands)->toHaveKey('settings:sync');
});

test('it loads migrations', function () {
    // Create a temporary directory for migrations
    $tempDir = sys_get_temp_dir() . '/fqn-settings-test-' . time();
    mkdir($tempDir);

    try {
        // Create a new application instance with the migrations path
        $app = $this->createApplication();
        $app['path.database'] = $tempDir;

        // Register the service provider
        $provider = new FqnSettingsServiceProvider($app);
        $provider->boot();

        // Run the migrate command to publish migrations
        Artisan::call('migrate', ['--database' => 'testing']);

        // Check if the fqn_settings table exists
        expect($this->app['db']->getSchemaBuilder()->hasTable('fqn_settings'))->toBeTrue();
    } finally {
        // Clean up
        if (is_dir($tempDir)) {
            File::deleteDirectory($tempDir);
        }
    }
});

test('it loads translations', function () {
    // Check if translations are loaded
    expect(trans()->has('fqn-settings::message.NoSettingSynchronized'))->toBeTrue();
});

test('it merges config', function () {
    // Check if the config is loaded
    expect($this->app['config']->has('fqn-settings'))->toBeTrue();
    expect($this->app['config']->has('fqn-settings.cache'))->toBeTrue();
    expect($this->app['config']->has('fqn-settings.fallback'))->toBeTrue();
});

test('it handles edge case when config directory does not exist', function () {
    // Create a new application instance
    $app = $this->createApplication();

    // Set a non-existent config path
    $app['path.config'] = '/non/existent/path';

    // Register the service provider
    $provider = new FqnSettingsServiceProvider($app);

    // This should not throw an exception
    try {
        $provider->boot();
        expect(true)->toBeTrue();
    } catch (Exception $e) {
        $this->fail('Exception thrown: ' . $e->getMessage());
    }
});
