<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function getDetails(): array
    {
        return [];
    }
}
