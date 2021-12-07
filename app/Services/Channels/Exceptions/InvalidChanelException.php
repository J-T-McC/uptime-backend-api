<?php

namespace App\Services\Channels\Exceptions;

use Exception;
use Throwable;

final class InvalidChanelException extends Exception
{
    public function __construct($message = "Notification channel not defined", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
