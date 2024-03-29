<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class TokenInvalidException extends ApiException
{
    public $type = "TOKEN_INVALID";
    /** @var string  */
    public $message = "Token is invalid.";
    /** @var int $code */
    public $code = 401;
}
