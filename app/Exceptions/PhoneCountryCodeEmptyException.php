<?php

namespace App\Exceptions;


class PhoneCountryCodeEmptyException extends ApiException
{
    public $type = "PHONE_COUNTRY_CODE_EMPTY";
    /** @var string  */
    public $message = "country_code is empty.";
    /** @var int $code */
    public $code = 400;
}
