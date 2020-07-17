<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class PhoneNumberOccupiedException extends ApiException
{
    public $type = "PHONE_NUMBER_OCCUPIED";
    public $message = 'The phone number is already in use.';
    public $code = 400;
}
