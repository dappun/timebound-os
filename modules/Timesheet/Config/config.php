<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'overtime' => [
        'notice' => 8 * 60 * 60,
        'warning' => 12 * 60 * 60
    ],

    'overlimit' => 16 * 60 * 60
];