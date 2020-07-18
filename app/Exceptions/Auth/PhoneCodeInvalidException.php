<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class PhoneCodeInvalidException extends ApiException
{
    public $type = "PHONE_CODE_INVALID";
    public $message = 'Phone code is invalid.';
    public $code = 400;
}
