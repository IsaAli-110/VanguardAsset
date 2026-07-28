<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | VanguardAsset: OOP Logic Engine (Python FastAPI)
    |--------------------------------------------------------------------------
    | URL of the Python FastAPI microservice that handles all OOP-based
    | calculations such as asset depreciation and audit log generation.
    */

    'asset_engine' => [
        'url' => env('ASSET_ENGINE_URL', 'http://127.0.0.1:8001'),
    ],

];
