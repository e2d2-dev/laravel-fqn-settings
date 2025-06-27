# Laravel FQN Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/e2d2-dev/laravel-fqn-settings.svg?style=flat-square)](https://packagist.org/packages/e2d2-dev/laravel-fqn-settings)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue?style=flat-square)](composer.json)

This package creates persistent settings for your Laravel application. Each setting is represented with its own class/file. Values can be read and written by calling the class statically.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Features](#features)
- [Usage](#usage)
  - [Creating Settings](#creating-settings)
  - [Retrieving Values](#retrieving-values)
  - [Updating Values](#updating-values)
  - [Default Values](#default-values)
  - [Type Safety](#type-safety)
- [Commands](#commands)
  - [Install Command](#install-command)
  - [Create Setting Command](#create-setting-command)
  - [Sync Settings Command](#sync-settings-command)
- [Advanced Features](#advanced-features)
  - [Autodiscovery](#autodiscovery)
  - [Caching](#caching)
  - [Package Support](#package-support)
- [Configuration](#configuration)
  - [Publishing Configuration](#publishing-configuration)
  - [Publishing Migrations](#publishing-migrations)
  - [Translations](#translations)
- [Troubleshooting](#troubleshooting)
- [Testing](#testing)

## Requirements

- PHP 8.2 or higher
- Laravel 9.0 or higher

## Installation

Install the package via Composer:

```shell
composer require e2d2-dev/laravel-fqn-settings
```

After installation, run the install command:

```shell
php artisan settings:install
```

This will add the sync-command to composer autoload dump - on every update all settings will be synchronized.

## Features

- **Type-safe settings**: Define the type of your settings and get type validation
- **Default values**: Set default values for your settings
- **Autodiscovery**: Settings are automatically discovered in your application
- **Caching**: Settings are cached for better performance
- **Encryption**: Sensitive settings can be encrypted in the database
- **Package support**: Packages can register their own settings
- **Fallback mechanism**: Settings can be saved to a JSON file as fallback

## Usage

### Creating Settings

Create a new setting using the artisan command:

```shell
php artisan make:setting
```

This will guide you through creating a new setting class:

```php
namespace App\Settings\SomeSetting;

use Betta\Settings\SettingAttribute;

class SomeSetting extends SettingAttribute
{
    protected ?string $value = '';
}
```

### Retrieving Values

Settings can be retrieved by calling the `get()` method:

```php
$value = SomeSetting::get();
```

### Updating Values

Settings can be updated by calling the `set()` method:

```php
SomeSetting::set('new-value');
```

### Default Values

Assign a value to the `$value` property. This will represent the default value, which will be set when a setting is first created in the database:

```php
protected string $value = 'default value';
```

### Type Safety

Change the type of `$value` and the system will throw an error when a type mismatch occurs:

```php
// This will only accept integers
protected int $value = 0;
```

## Commands

### Install Command

```shell
php artisan settings:install
```

This sets up the package and adds the sync command to composer autoload dump.

### Create Setting Command

```shell
php artisan make:setting
```

Interactive command to create a new setting class.

### Sync Settings Command

```shell
php artisan settings:sync
```

Manually start synchronization of settings between your code and the database.

## Advanced Features

### Autodiscovery

All settings in the `App\Settings` namespace will be discovered automatically.
To add further directories and namespaces, add them to the configuration file "fqn-settings":

```php
'discover' => [
    // 'vendor/some-vendor/package/src/Settings' => 'Vendor\\Package\\Settings',
],
```

### Caching

Settings are cached for better performance. You can configure caching behavior in the config file:

```php
'cache' => [
    'enabled' => true,
],
```

### Encryption

Settings can be encrypted for sensitive data. To enable encryption for a setting, set the `encrypt` attribute to `true`:

```php
$setting = FqnSetting::create([
    'key' => 'api_key',
    'fqn' => 'App\\Settings\\ApiKey',
    'value' => 'secret-api-key',
    'encrypt' => true,
]);
```

When encryption is enabled:
- The value is automatically encrypted when saved to the database
- The value is automatically decrypted when retrieved from the database
- The encryption uses Laravel's built-in encryption system

### Package Support

Packages can have their own settings and register them inside a service provider.
Add this to the "boot" method of a service provider to register them for synchronization:

```php
use Betta\Settings\Settings;

public function boot() {
    Settings::path('{package-root}/src/Settings', 'Vendor\\Package\\Settings');
}
```

## Configuration

### Publishing Configuration

Publish the configuration file:

```shell
php artisan vendor:publish --tag=laravel-fqn-settings-config
```

This will create a `fqn-settings.php` file in your config directory with the following options:

- **Cache**: Enable/disable caching
- **Fallback**: Configure JSON fallback for settings
- **Discover**: Add additional directories to discover settings

### Publishing Migrations

If you need to customize the database migrations, you can publish them:

```shell
php artisan vendor:publish --tag=laravel-fqn-settings-migrations
```

This will copy the migration files to your application's `database/migrations` directory.

### Translations

The package comes with translations in English and German that are loaded automatically. The translations are located in the package's `resources/lang` directory.

If you want to customize the translations, you can publish them to your application:

```shell
php artisan vendor:publish --tag=laravel-fqn-settings-translations
```

This will copy the translation files to your application's `resources/lang/vendor/fqn-settings` directory. You can then modify these files to customize the translations. Laravel will prioritize your custom translations over the package's translations.

## Troubleshooting

- **Settings not being discovered**: Make sure your settings are in the correct namespace and follow the proper class structure
- **Cache issues**: Try clearing the cache with `php artisan cache:clear`
- **Database sync issues**: Run `php artisan settings:sync` manually to force synchronization

## Testing

This package uses [Pest PHP](https://pestphp.com/) for testing. Pest is a testing framework with a focus on simplicity and developer experience.

### Running Tests

To run the tests, use the following command:

```shell
composer test
```

### Writing Tests

The package includes tests for models, providers, and other components. You can use these as examples for writing your own tests.

Tests are located in the `tests` directory and follow the Pest syntax:

```php
test('it can create a setting', function () {
    $setting = FqnSetting::create([
        'key' => 'test_key',
        'fqn' => 'App\\Settings\\TestSetting',
        'value' => 'test_value',
    ]);

    expect($setting->value)->toBe('test_value');
});
```

### Test Setup

The package includes a base `TestCase` class that sets up the testing environment with the necessary service providers and database configuration. You can extend this class for your own tests.
