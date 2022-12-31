<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ValidationNotAcceptableException extends Exception
{
    public function __construct($errors, $message, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
