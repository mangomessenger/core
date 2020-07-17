<?php

namespace App\Exceptions;

class PhoneNumberEmptyException extends ApiException
{
    public $type = "PHONE_NUMBER_EMPTY";
    /** @var string  */
    public $message = "phone_number is empty.";
    /** @var int $code */
    public $code = 400;
}
