<?php

return [
    'global' => [
        // max requests per `decay_minutes` for same IP + route
        'max_requests' => env('RATE_LIMIT_GLOBAL_MAX', 300),
        'decay_minutes' => env('RATE_LIMIT_GLOBAL_DECAY_MINUTES', 1),
    ],

    'auth' => [
        'attempt_ttl_seconds' => env('RATE_LIMIT_AUTH_ATTEMPT_TTL', 3600),
        'lockout_threshold' => env('RATE_LIMIT_AUTH_LOCKOUT_THRESHOLD', 10),
        'max_lock_minutes' => env('RATE_LIMIT_AUTH_MAX_LOCK_MINUTES', 60),
    ],
];
