<?php

namespace App\Exceptions;

use Exception;
use Throwable;

final class UndefinedPropertyException extends Exception
{
    public function __construct(string $message = "Undefined property", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
