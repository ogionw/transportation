<?php

namespace App\Domain\Exception;

use Exception;

class InvalidPeopleNumberException extends Exception
{
    public function __construct(int $seatsNumber = 0, int $groupId = 0)
    {
        $message = sprintf('Invalid people number: %d in group number %d', $seatsNumber, $groupId);
        parent::__construct($message, 0, null);
    }
}