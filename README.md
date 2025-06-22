# FQN Settings
This package creates persistent settings for your app. Each setting will be available as a class. Values can be read and written by calling the class statically.

## Features
- [Autodiscovery](#autodiscovery)
- [Default Values](#default-values)


## Content
- [Getting Started](#getting-started)
- [Setting Class](#setting-class)


## Installation

Install Using Composer

```shell
composer require e2d2-dev/filament-fqn-settings
```

## Install Command

This will add the sync-command to composer autoload dump - on every update all settings will be synchronized.

```shell
php artisan settings:install
```


# Getting Started


## Create Setting Command
```shell
php artisan make:setting
```

### Class
```php
namespace App\Settings\SomeSetting;

use Betta\Settings\SettingAttribute;

class SomeSetting extends SettingAttribute
{
    protected ?string $value = '';
}
```

### Default Values
Assign a value to the key of ```$value```. This will represent the default value, which will be set, when a setting is sent to the database.

### Return Type
Change the return type of ```$value``` and the value will throw an error when a type mismatch occurs.

### Retrieving
Settings can be retrieved by calling the get-method
```php
SomeSetting::get()
```

### Updating
Settings can be updated by calling the set-method
```php
SomeSetting::set('some-value')
```

## Sync Settings Command
Manually start synchronization of settings.

```shell
php artisan settings:sync
```


## Autodiscovery
All Settings in the App\Settings namespace will be discovered automatically.
To add further directories and namespaces, add them to the configuration file "fqn-settings".

```php
    'discover' => [
        // 'vendor/some-vendor/package/src/Settings' => 'Vendor\\Package\\Settings',
    ],
```


## Vendor Packages
Packages can have their own settings and are able to register them inside a service provider.
Add this to the "boot" method of a service provider to register them for synchronization.

```php
use Betta\Settings\Settings;

public function boot() {
    Settings::path('{package-root}/src/Settings', 'Vendor\\Package\\Settings');
}
```
