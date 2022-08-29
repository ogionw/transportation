<?php

declare(strict_types=1);

namespace App\Application\Cqrs;

use App\Presentation\Message\Command;

interface CommandBusInterface
{
    public function dispatch(Command $command): void;
}
