<?php

namespace LeonardoVee\CircuitBreaker\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use LeonardoVee\CircuitBreaker\CircuitBreakerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CircuitBreakerServiceProvider::class,
        ];
    }
}
