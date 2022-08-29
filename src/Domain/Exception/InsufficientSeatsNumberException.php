<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class InsufficientSeatsNumberException extends Exception
{
    public function __construct(int $freeSeats = 0, int $carId = 0)
    {
        $message = sprintf('Insufficient free seats number: %d in car number %d', $freeSeats, $carId);
        parent::__construct($message, 0, null);
    }
}