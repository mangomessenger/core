<?php

namespace App\Exceptions;

class TestException extends ApiException
{
    public string $type = "TEST_ERROR_EXCEPTION";
    public $code = 228;
}
