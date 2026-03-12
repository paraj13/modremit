<?php

return [
    'revolut' => [
        'api_key'    => env('REVOLUT_API_KEY', ''),
        'base_url'   => env('REVOLUT_BASE_URL', 'https://sandbox-b2b.revolut.com/api/1.0'),
        'client_id'  => env('REVOLUT_CLIENT_ID', ''),
    ],

    'sumsub' => [
        'app_token'  => env('SUMSUB_APP_TOKEN', ''),
        'secret_key' => env('SUMSUB_SECRET_KEY', ''),
        'base_url'   => env('SUMSUB_BASE_URL', 'https://api.sumsub.com'),
        'level_name' => env('SUMSUB_LEVEL_NAME', 'id-and-liveness'),
    ],
];
