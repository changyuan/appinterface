<?php
//
return [
    
    'debug' => env('APP_DEBUG', false),
    
    'name' => env('APP_NAME', 'YLVS'),

    'shop_url' => env('SHOP_URL', ''),

    'share_url' => env('SHARE_URL', ''),

    'shop_h5' => env('SHOP_H5', ''),

    'json_cdn' => env('JSON_CDN', ''),

    'hosts' => env('HOSTS', ''),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', 'SomeRandomString!!!'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'locale' => env('APP_LOCALE', 'en'),
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),


    'log' => 'daily',
    'log_max_files' => 30,

    'close_order_time' => 30,//未支付订单超时自动关闭订单，单位：分钟
    'close_trade_time' => 15,//发货后达到指定天数后自动关闭交易，单位：天
];
