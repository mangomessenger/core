<?php

namespace App\Http\Middleware;

use App\Exceptions\TimeoutException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Middleware\ThrottleRequests as Throttle;

class ThrottleRequests extends Throttle
{
    /**
     * Create a 'too many attempts' exception.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return ThrottleRequestsException
     */
    protected function buildException($key, $maxAttempts)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return new TimeoutException(
            "Too many requests were sent in a given amount of time. Please wait {$retryAfter} seconds.", null, $headers, 0, $retryAfter
        );
    }
}
