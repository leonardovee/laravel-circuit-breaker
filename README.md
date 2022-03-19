# Laravel Circuit Breaker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/:vendor_slug/:package_slug/run-tests?label=tests)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/:vendor_slug/:package_slug/Check%20&%20fix%20styling?label=code%20style)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)

An implementation of the Circuit Breaker pattern for Laravel Framework 9

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
    /**  The time that the CircuitBreaker should wait before transitioning from open to open. */
    'openCircuitTimeWindow' => 60,
    /** When the failure rate is equal or greater than the threshold the CircuitBreaker transitions to open */
    'failureRateThreshold' => 50
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
