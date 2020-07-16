<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class TimeoutException extends ThrottleRequestsException
{
    public $timeout;

    public function __construct($message = null, Throwable $previous = null, array $headers = [], $code = 0, $timeout = 0)
    {
        $this->timeout = $timeout;
        parent::__construct($message, $previous, $headers, $code);
    }
}
