<?php
declare(strict_types=1);

namespace App\Application\Cqrs;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class EventBus implements EventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @throws Throwable
     */
    public function dispatch(DomainEventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
