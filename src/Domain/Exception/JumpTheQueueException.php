<?php

namespace App\Domain\Exception;

use Exception;

class JumpTheQueueException extends Exception
{
    public function __construct(int $groupId = 0)
    {
        $message = sprintf('Gango ID: %d has attempted to jump the queue', $groupId);
        parent::__construct($message, 0, null);
    }
}