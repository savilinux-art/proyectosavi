<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Traccar Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('TRACCAR_BASE_URL', 'http://localhost:8082'),

    /*
    |--------------------------------------------------------------------------
    | Traccar API Key
    |--------------------------------------------------------------------------
    | Nunca debe ser null: el ServiceProvider del paquete tipa estricto
    | y lanza InvalidArgumentException si el valor es NULL.
    */
    'api_key' => env('TRACCAR_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Credenciales opcionales (algunos endpoints las usan)
    |--------------------------------------------------------------------------
    */
    'email'    => env('TRACCAR_EMAIL', ''),
    'password' => env('TRACCAR_PASSWORD', ''),
];