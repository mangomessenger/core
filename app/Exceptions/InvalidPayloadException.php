<?php

namespace App\Exceptions;

class InvalidPayloadException extends ApiException
{
    public $type = "INVALID_PAYLOAD";
    public $message = 'The given data was invalid.';
    public array $errors = [];
    public $code = 422;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
