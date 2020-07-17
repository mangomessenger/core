<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class RefreshTokenInvalidException extends ApiException
{
    public $type = "REFRESH_TOKEN_INVALID";
    /** @var string  */
    public $message = "Refresh token is invalid.";
    /** @var int $code */
    public $code = 401;
}
