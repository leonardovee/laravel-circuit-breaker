<?php

namespace LeonardoVee\CircuitBreaker\Enums;

enum CircuitBreakerStates: string
{
    case CLOSED = 'closed';
    case HALF_OPEN = 'half_open';
    case OPEN = 'open';
}
