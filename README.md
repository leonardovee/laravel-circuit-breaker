# Laravel Circuit Breaker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leonardovee/laravel-circuit-breaker.svg?style=flat-square)](https://packagist.org/packages/leonardovee/laravel-circuit-breaker)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/leonardovee/laravel-circuit-breaker/run-tests?label=tests)](https://github.com/leonardovee/laravel-circuit-breaker/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/leonardovee/laravel-circuit-breaker/Check%20&%20fix%20styling?label=code%20style)](https://github.com/leonardovee/laravel-circuit-breaker/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/leonardovee/laravel-circuit-breaker.svg?style=flat-square)](https://packagist.org/packages/leonardovee/laravel-circuit-breaker)

A straightforward implementation of the Circuit Breaker pattern for Laravel Framework 9 (using Memcached).

## Installation

You can install the package via composer:

```bash
composer require leonardovee/laravel-circuit-breaker
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="circuit-breaker-config"
```

This is the contents of the published config file:

```php
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
```

## Usage

```php
use LeonardoVee\CircuitBreaker\CircuitBreaker;

try {
    /** Make the request to your service */
    Http::get('https://my-service.com');
    
    /** Register a success on the circuit breaker */
    CircuitBreaker::setSuccess(circuitName: 'https://my-service.com');
} catch (Throwable $throwable) {
    /** Register a failure on the circuit breaker */
    CircuitBreaker::setFailure(circuitName: 'https://my-service.com');
}

/** Use to verify if the circuit is closed or open */
if (CircuitBreaker::isAvailable(circuitName: 'https://my-service.com')) {
    /** Do stuff */
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [leonardovee](https://github.com/leonardovee)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
