<?php

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    public string $type = "ERROR_OCCURRED";
    public $code = 500;
}
