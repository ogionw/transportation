<?php

declare(strict_types=1);

namespace App\Application\Cqrs;

interface EventBusInterface
{
    public function dispatch(DomainEventInterface $event): void;
}
