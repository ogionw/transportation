<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\JourneyEndedEvent;
use App\Domain\Model\Transportation;

final class CarReturnsListener implements EventHandler
{
    public function __construct(private Transportation $transportation){}

    public function __invoke(JourneyEndedEvent $journeyEndedEvent)
    {
        $this->transportation->run();
    }
}
