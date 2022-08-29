<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\JourneyRequestedEvent;
use App\Domain\Model\Transportation;

final class NewJourneyListener implements EventHandler
{
    public function __construct(private Transportation $transportation){}

    public function __invoke(JourneyRequestedEvent $journeyRequestedEvent)
    {
        $this->transportation->run();
    }
}
