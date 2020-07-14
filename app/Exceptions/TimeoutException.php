<?php

namespace App\Exceptions;

class TimeoutException extends ApiException
{
    private const TYPE = "TIMEOUT_WAIT_";
    private int $timeout;

    public $code = 222;

    public function __construct($message = "", int $timeout = 0)
    {
        $this->timeout = $timeout;
        $this->type = self::TYPE . $timeout;

        parent::__construct($message, $this->code, null);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type . $this->timeout;
    }
}
