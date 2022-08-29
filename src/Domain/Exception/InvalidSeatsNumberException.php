<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class InvalidSeatsNumberException extends Exception
{
    public function __construct(int $seatsNumber = 0, int $carId = 0)
    {
        $message = sprintf('Invalid seats number: %d in car number %d', $seatsNumber, $carId);
        parent::__construct($message, 0, null);
    }
}