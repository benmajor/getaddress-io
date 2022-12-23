<?php

namespace BenMajor\GetAddress\Exception;

use Exception;

class RequestException extends Exception
{
    public function __construct(string $message = null, int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
