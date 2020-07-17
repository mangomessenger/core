<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class AuthRequestExpiredException extends ApiException
{
    public $type = "AUTH_REQUEST_EXPIRED";
    public $message = 'Auth request expired.';
    public $code = 400;
}
