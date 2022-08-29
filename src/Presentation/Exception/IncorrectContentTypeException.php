<?php

namespace App\Presentation\Exception;

use Exception;
use Throwable;

class IncorrectContentTypeException extends Exception
{
    public function __construct(string $contentType)
    {
        $message = sprintf('Incorrect content type: "%s"', $contentType);
        parent::__construct($message, 0, null);
    }
}