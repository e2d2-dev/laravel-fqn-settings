<?php

use Betta\Settings\Models\FqnSetting;
use Betta\Settings\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(TestCase::class, RefreshDatabase::class);

test('it can create a setting', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'test_value',
        'default' => 'default_value',
        'type' => 'string',
    ]);

    $this->assertDatabaseHas('fqn_settings', [
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
    ]);

    expect($setting->value)->toBe('test_value');
    expect($setting->default)->toBe('default_value');
});

test('it can update a setting', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'test_value',
        'default' => 'default_value',
        'type' => 'string',
    ]);

    $setting->value = 'updated_value';
    $setting->save();

    $this->assertDatabaseHas('fqn_settings', [
        'key' => 'test_key',
        'value' => json_encode('updated_value'),
    ]);

    $refreshedSetting = FqnSetting::find($setting->id);
    expect($refreshedSetting->value)->toBe('updated_value');
});

test('it can be marked as lost', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'test_value',
    ]);

    expect($setting->lost_at)->toBeNull();

    $setting->markLost();

    expect($setting->lost_at)->not->toBeNull();
    expect($setting->isLost())->toBeTrue();
});

test('it can encrypt values', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'secret_value',
        'encrypt' => true,
    ]);

    // The value should be encrypted in the database
    $rawSetting = app('db')->table('fqn_settings')->where('id', $setting->id)->first();
    expect($rawSetting->value)->not->toBe(json_encode('secret_value'));

    // But the model should decrypt it automatically
    expect($setting->value)->toBe('secret_value');
});

test('it does not encrypt values when encrypt is false', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'non_secret_value',
        'encrypt' => false,
    ]);

    // The value should not be encrypted in the database
    $rawSetting = app('db')->table('fqn_settings')->where('id', $setting->id)->first();
    expect($rawSetting->value)->toBe(json_encode('non_secret_value'));

    // The model should return the value as is
    expect($setting->value)->toBe('non_secret_value');
});

test('it can cache values', function () {
    // Clear any existing cache
    Cache::flush();

    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'cached_value',
    ]);

    // Initially, the value should not be cached
    expect($setting->isCached())->toBeFalse();

    // Cache the value
    $setting->cache();

    // Now it should be cached
    expect($setting->isCached())->toBeTrue();
    expect(Cache::get($setting->getCacheKey()))->toBe('cached_value');

    // Update the value without updating the cache
    $setting->value = 'updated_value';
    $setting->save();

    // The cache should now differ from the actual value
    expect($setting->cacheDiffers())->toBeTrue();
    expect(Cache::get($setting->getCacheKey()))->toBe('cached_value');

    // Forget the cache
    $setting->forgetCache();

    // The cache should be cleared
    expect($setting->isCached())->toBeFalse();
});

test('it handles json values correctly', function () {
    $arrayValue = ['key1' => 'value1', 'key2' => 'value2'];

    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => $arrayValue,
    ]);

    expect($setting->value)->toBe($arrayValue);

    $refreshedSetting = FqnSetting::find($setting->id);
    expect($refreshedSetting->value)->toBe($arrayValue);
});

test('it handles null values when nullable', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => null,
        'nullable' => true,
    ]);

    expect($setting->value)->toBeNull();

    $refreshedSetting = FqnSetting::find($setting->id);
    expect($refreshedSetting->value)->toBeNull();
});

test('it generates snake case fqn', function () {
    $setting = new FqnSetting([
        'fqn' => 'App\\Settings\\TestSetting',
    ]);

    expect($setting->getSnakeFqn())->toBe('app_settings_test_setting');
});
