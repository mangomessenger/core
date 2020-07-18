<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class PhoneCodeHashInvalidException extends ApiException
{
    public $type = "PHONE_CODE_HASH_INVALID";
    public $message = 'Phone code hash is invalid.';
    public $code = 400;
}
