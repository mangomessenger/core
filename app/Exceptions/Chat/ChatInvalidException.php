<?php

namespace App\Exceptions\Chat;

use App\Exceptions\ApiException;

class ChatInvalidException extends ApiException
{
    public $type = "CHAT_INVALID";
    public $message = "Chat is invalid.";
    public $code = 400;
}
