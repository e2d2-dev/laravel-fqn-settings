<?php

use Betta\Settings\Collections\SyncLogCollection;
use Betta\Settings\Registry;
use Mockery;

beforeEach(function () {
    $this->registry = new Registry();
});

afterEach(function () {
    Mockery::close();
});

test('it can get discovered settings', function () {
    // Set some test settings
    $this->registry->settings = ['App\\Settings\\TestSetting1', 'App\\Settings\\TestSetting2'];

    // Verify getDiscoveredSettings returns the settings
    expect($this->registry->getDiscoveredSettings())->toBe(['App\\Settings\\TestSetting1', 'App\\Settings\\TestSetting2']);
});

test('it can sync settings and return a SyncLogCollection', function () {
    // Create a new Registry instance
    $registry = new Registry();

    // Call sync
    $result = $registry->sync();

    // Verify the result is a SyncLogCollection
    expect($result)->toBeInstanceOf(SyncLogCollection::class);
});

test('it initializes with empty settings and synced arrays', function () {
    $registry = new Registry();

    expect($registry->settings)->toBe([]);
    expect($registry->synced)->toBe([]);
});
