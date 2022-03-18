<?php

namespace LeonardoVee\CircuitBreaker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LeonardoVee\CircuitBreaker\CircuitBreaker
 */
class CircuitBreaker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'circuit-breaker';
    }
}
