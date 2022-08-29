<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;

class JourneyEndedEvent implements DomainEventInterface
{
    public function __construct(public int $id){}
}