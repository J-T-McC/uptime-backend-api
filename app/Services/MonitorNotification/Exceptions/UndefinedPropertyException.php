<?php


namespace App\Services\MonitorNotification\Exceptions;

use Exception;
use Throwable;

final class UndefinedPropertyException extends Exception
{
    public function __construct($message = "Undefined property", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
