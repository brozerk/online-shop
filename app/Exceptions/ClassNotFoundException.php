<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ClassNotFoundException extends Exception
{
    public function __construct(string $message = "the class is not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}