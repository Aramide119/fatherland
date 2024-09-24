<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

        'paths' => ['api/*', 
        'passport/csrf-cookie',
        'sanctum/csrf-cookie'
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

    // 'allowed_origins' => ['http://example.com', 'https://example.net', 'http://localhost:3000', 'https://fatherland-accessories.vercel.app', 'https://fameet.vercel.app', 'https://fameet.com', 'https://www.fameet.com', 'fameet.com'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];