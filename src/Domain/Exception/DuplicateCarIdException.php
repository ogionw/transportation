<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class DuplicateCarIdException extends Exception
{
    public function __construct(int $carId = 0)
    {
        $message = sprintf('Duplicate Car ID: %d', $carId);
        parent::__construct($message, 0, null);
    }
}