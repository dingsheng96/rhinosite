<?php

return [

    'prefix' => false, // if true, prefix mode will be used, else will use domain mode

    'web' => [
        'web' => [
            'url' => env('MAIN_DOMAIN'),
            'prefix' => '',
            'namespace' => '',
            'route' => [
                'name' => '',
                'file' => 'web.php',
            ]
        ],
        'admin' => [
            'url' => env('WEB_ADMIN_DOMAIN'),
            'prefix' => 'admin',
            'namespace' => 'Admin',
            'route' => [
                'name' => 'admin',
                'file' => 'web/admin.php',
            ]
        ],
        'merchant' => [
            'url' => env('WEB_MERCHANT_DOMAIN'),
            'prefix' => 'merchant',
            'namespace' => 'Merchant',
            'route' => [
                'name' => 'merchant',
                'file' => 'web/merchant.php'
            ]
        ],
    ],
];
