<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class DuplicateGroupIdException extends Exception
{
    public function __construct(int $groupId = 0)
    {
        $message = sprintf('Duplicate Gango ID: %d', $groupId);
        parent::__construct($message, 0, null);
    }
}