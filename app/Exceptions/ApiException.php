<?php

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    public $type = "ERROR_OCCURRED";
    public $code = 500;
}
