<?php

namespace LeonardoVee\CircuitBreaker\Tests;

use LeonardoVee\CircuitBreaker\CircuitBreakerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CircuitBreakerServiceProvider::class,
        ];
    }
}
