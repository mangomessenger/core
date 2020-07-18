<?php

namespace App\Exceptions\JWT;

use App\Exceptions\ApiException;

class FingerprintInvalidException extends ApiException
{
    public $type = "FINGERPRINT_INVALID";
    /** @var string  */
    public $message = "Fingerprint is invalid.";
    /** @var int $code */
    public $code = 400;
}
