<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class JWTTokenAbsentException extends ApiException
{
    public $type = "TOKEN_ABSENT";
    /** @var string  */
    public $message = "Token is absent.";
    /** @var int $code */
    public $code = 401;
}

