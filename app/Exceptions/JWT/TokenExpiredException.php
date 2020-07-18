<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class TokenExpiredException extends ApiException
{
    public $type = "TOKEN_EXPIRED";
    /** @var string  */
    public $message = "Token has expired.";
    /** @var int $code */
    public $code = 401;
}
