<?php

return [
    'memcached-server' => [
        'host' => '127.0.0.1',
        'port' => 11211
    ],
    'circuit-breaker' => [
        'failure-threshold' => 5,
        'half-open-timeout' => 5,
        'timeout' => 10
    ]
];
