<?php

namespace App\Exceptions\Message;

use App\Exceptions\ApiException;

class ChatTypeInvalidException extends ApiException
{
    public $type = "CHAT_TYPE_INVALID";
    /** @var string  */
    public $message = "Chat type is invalid.";
    /** @var int $code */
    public $code = 400;
}
