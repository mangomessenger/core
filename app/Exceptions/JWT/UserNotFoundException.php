<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class UserNotFoundException extends ApiException
{
    public $type = "TOKEN_USER_NOT_FOUND";
    /** @var string  */
    public $message = "Token user not found.";
    /** @var int $code */
    public $code = 401;
}
