<?php

return [

    'prefix' => false, // if true, prefix mode will be used, else will use domain mode

    'web' => [
        'admin' => [
            'url' => env('WEB_ADMIN_DOMAIN'),
            'prefix' => 'admin',
            'namespace' => 'Admin',
            'route' => [
                'name' => 'admin',
                'file' => 'admin.php',
            ]
        ],
        'merchant' => [
            'url' => env('WEB_MERCHANT_DOMAIN'),
            'prefix' => 'merchant',
            'namespace' => 'Merchant',
            'route' => [
                'name' => 'merchant',
                'file' => 'merchant.php'
            ]
        ],

        'member' => [
            'url' => env('APP_URL'),
            'prefix' => 'member',
            'namespace' => 'Member',
            'route' => [
                'name' => 'member',
                'file' => 'member.php'
            ]
        ],
    ],
];
