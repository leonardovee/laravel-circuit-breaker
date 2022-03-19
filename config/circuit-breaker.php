<?php

/**
 * Config for \LeonardoVee\CircuitBreaker\CircuitBreaker
 */
return [
    'memcached-server' => [
        'host' => '127.0.0.1',
        'port' => 11211
    ],
    'circuit-breaker' => [
        'failure-threshold' => 5,
        'timeout' => 5
    ]
];
