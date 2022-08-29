<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class GroupNotFoundException extends Exception
{
    public const NOT_FOUND = "Group not found!";
    public function __construct()
    {
        parent::__construct(self::NOT_FOUND, 0, null);
    }
}