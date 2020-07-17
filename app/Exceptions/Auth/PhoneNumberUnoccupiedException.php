<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class PhoneNumberUnoccupiedException extends ApiException
{
    public $type = "PHONE_NUMBER_UNOCCUPIED";
    public $message = 'The code is valid but no user with the given number is registered.';
    public $code = 400;
}
