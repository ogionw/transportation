<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;

class JourneyRequestedEvent implements DomainEventInterface
{
    public function __construct(private int $id){}
}