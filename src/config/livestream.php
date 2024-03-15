<?php

return[

    /*
    |--------------------------------------------------------------------------
    | Livestream Endpoint
    |--------------------------------------------------------------------------
    |
    | This value is endpoint of wowza api
    |
    */

    'livestream_endpoint' => env('LIVESTREAM_ENDPOINT', 'endpoint_value'),

    /*
    |--------------------------------------------------------------------------
    | Livestream Token
    |--------------------------------------------------------------------------
    |
    | This value is access token of wowza api which will get from wowza account
    |
    */

    'livestream_token' => env('LIVESTREAM_TOKEN', 'token_value')
];