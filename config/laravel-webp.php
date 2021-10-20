<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Quality
    |--------------------------------------------------------------------------
    |
    | This is a default quality unless you provide while generation of the WebP
    |
    */

    'default_quality' => 80,

    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This is a default image processing driver. Available: ['cwebp']
    |
    */

    'default_driver' => 'cwebp',

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Available drivers which can be selected
    |
    */

    'drivers' => [

        'cwebp' => [
            'path' => 'C:/Users/clynton.manaois/Downloads/libwebp-1.2.1-windows-x64/libwebp-1.2.1-windows-x64/bin/cwebp',
        ],

    ],
];
