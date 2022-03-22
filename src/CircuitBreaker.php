<?php

namespace LeonardoVee\CircuitBreaker;

use LeonardoVee\CircuitBreaker\Enums\CircuitBreakerStates;
use Memcached;

class CircuitBreaker
{
    public static string $memcachedServerHost = 'localhost';
    public static int $memcachedServerPort = 11211;
    private static string $memcachedServerPrefix = 'circuit-breaker-';

    private const memcachedServerCircuitFailuresCountSuffix = '-failures-count';
    private const memcachedServerCircuitState = '-state';

    public static function isAvailable(string $circuitName): bool
    {
        if (self::isCircuitOpen(circuitName: $circuitName)) {
            return false;
        }

        if (self::isCircuitThresholdReached($circuitName)) {
            self::setState(circuitName: $circuitName, state: CircuitBreakerStates::OPEN);

            return false;
        }

        return true;
    }

    public static function setSuccess(string $circuitName): void
    {
        $memcachedServer = self::getMemcachedServer();

        $memcachedServer->delete(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitFailuresCountSuffix
        );
        self::setState(circuitName: $circuitName, state: CircuitBreakerStates::CLOSED);
    }

    public static function setFailure(string $circuitName)
    {
        $memcachedServer = self::getMemcachedServer();

        $doMemcachedServerHasFailuresCountKey = $memcachedServer->get(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitFailuresCountSuffix
        );

        if (! $doMemcachedServerHasFailuresCountKey) {
            $memcachedServer->set(
                key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitFailuresCountSuffix,
                value: 0,
                expiration: config(key: 'circuit-breaker.circuit-breaker.timeout')
            );
        }

        $memcachedServer->increment(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitFailuresCountSuffix
        );
    }

    private static function getMemcachedServer(): Memcached
    {
        $memcachedServer = new Memcached();

        $memcachedServer->addServer(
            host: config(key: 'circuit-breaker.memcached-server.host') ?? self::$memcachedServerHost,
            port: config(key: 'circuit-breaker.memcached-server.port') ?? self::$memcachedServerPort
        );

        return $memcachedServer;
    }

    private static function isCircuitOpen(string $circuitName): bool
    {
         $circuitState = self::getState($circuitName);

         return $circuitState === CircuitBreakerStates::OPEN->value;
    }

    private static function isCircuitThresholdReached(string $circuitName): bool
    {
        $memcachedServer = self::getMemcachedServer();

        $failuresCount = $memcachedServer->get(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitFailuresCountSuffix
        );

        return $failuresCount > config(key: 'circuit-breaker.circuit-breaker.failure-threshold');
    }

    private static function getState(string $circuitName): string
    {
        $memcachedServer = self::getMemcachedServer();

        return $memcachedServer->get(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitState
        );
    }

    private static function setState(string $circuitName, CircuitBreakerStates $state): void
    {
        $memcachedServer = self::getMemcachedServer();

        $memcachedServer->set(
            key: self::$memcachedServerPrefix . $circuitName . self::memcachedServerCircuitState,
            value: $state->value,
            expiration: config(key: 'circuit-breaker.circuit-breaker.timeout')
        );
    }
}
