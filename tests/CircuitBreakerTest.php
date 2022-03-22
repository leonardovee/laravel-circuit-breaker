<?php

use LeonardoVee\CircuitBreaker\CircuitBreaker;

beforeEach(function () {
    $memcachedServer = new Memcached();
    $memcachedServer->addServer(
        host: CircuitBreaker::$memcachedServerHost,
        port: CircuitBreaker::$memcachedServerPort
    );
    $memcachedServer->flush();
});

it(description: 'should return if the circuit is available', closure: function () {
    $isCircuitAvailable = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitAvailable)->toBeTrue();
});

it(description: 'should open the circuit on failure', closure: function () {
    for ($i = 0; $i < 6; $i++) {
        CircuitBreaker::setFailure(circuitName: 'testing');
    }

    /** force a CircuitBreaker::isCircuitOpen() call on next CircuitBreaker::isAvailable() */
    CircuitBreaker::isAvailable(circuitName: 'testing');

    $isCircuitAvailable = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitAvailable)->toBeFalse();
});

it(description: 'should closed the circuit after a success', closure: function () {
    for ($i = 0; $i < 6; $i++) {
        CircuitBreaker::setFailure(circuitName: 'testing');
    }

    CircuitBreaker::setSuccess(circuitName: 'testing');

    $isCircuitAvailable = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitAvailable)->toBeTrue();
});

it(description: 'should close the circuit after 15 seconds', closure: function () {
    for ($i = 0; $i < 6; $i++) {
        CircuitBreaker::setFailure(circuitName: 'testing');
    }

    /** force a CircuitBreaker::isCircuitOpen() call on next CircuitBreaker::isAvailable() */
    CircuitBreaker::isAvailable(circuitName: 'testing');

    sleep(seconds: config(key: 'circuit-breaker.circuit-breaker.timeout'));

    $isCircuitAvailable = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitAvailable)->toBeTrue();
});

it(description: 'should half open the circuit after 5 seconds', closure: function () {
    for ($i = 0; $i < 6; $i++) {
        CircuitBreaker::setFailure(circuitName: 'testing');
    }

    CircuitBreaker::isAvailable(circuitName: 'testing');

    sleep(seconds: config(key: 'circuit-breaker.circuit-breaker.half-open-timeout') + 1);

    $isCircuitHalfOpen = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitHalfOpen)->toBeTrue();
});

it(description: 'should open the circuit if a half open circuit sees a failure', closure: function () {
    for ($i = 0; $i < 6; $i++) {
        CircuitBreaker::setFailure(circuitName: 'testing');
    }

    CircuitBreaker::isAvailable(circuitName: 'testing');

    sleep(seconds: config(key: 'circuit-breaker.circuit-breaker.half-open-timeout') + 1);

    CircuitBreaker::isAvailable(circuitName: 'testing');

    CircuitBreaker::setFailure(circuitName: 'testing');

    $isCircuitAvailable = CircuitBreaker::isAvailable(circuitName: 'testing');
    expect(value: $isCircuitAvailable)->toBeFalse();
});
