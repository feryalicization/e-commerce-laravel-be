<?php

return [
    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
    'route_middleware' => [ // Changed to a valid array key
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ],
];
