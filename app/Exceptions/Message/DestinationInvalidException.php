<?php

namespace App\Exceptions\Message;

use App\Exceptions\ApiException;

class DestinationInvalidException extends ApiException
{
    public $type = "DESTINATION_INVALID";
    /** @var string  */
    public $message = "Destination is invalid.";
    /** @var int $code */
    public $code = 400;
}
