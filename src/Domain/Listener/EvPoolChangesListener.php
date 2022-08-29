<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\EvPoolReplacedEvent;
use App\Domain\Model\Transportation;

final class EvPoolChangesListener implements EventHandler
{
    public function __construct(private Transportation $transportation){}

    public function __invoke(EvPoolReplacedEvent $evPoolReplacedEvent)
    {
        $this->transportation->run();
    }
}
