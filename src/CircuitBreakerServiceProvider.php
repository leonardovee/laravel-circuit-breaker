<?php

namespace LeonardoVee\CircuitBreaker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CircuitBreakerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name(name: 'circuit-breaker')
            ->hasConfigFile();
    }
}
