<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Options for caching. Set whether to enable cache or its key
    |
    */
    'cache' => [
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback
    |--------------------------------------------------------------------------
    |
    | Saves all settings to the file specified
    |
    */

    'fallback' => [
        'enabled' => false,
        'file' => config_path('setting-fallback.json'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Discover
    |--------------------------------------------------------------------------
    |
    | Include more directories to discover settings
    |
    */
    'discover' => [
        // 'app-modules/settings/src/SomePackage' => 'Vendor\\Package\\Settings',
    ],
];
