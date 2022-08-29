<?php
declare(strict_types=1);

namespace App\Application\Command;
use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\EvPoolReplacedEvent;
use App\Domain\Model\Transportation;
use App\Presentation\Message\ReplaceCarsCommand;

final class ReplaceCarsCommandReaction implements CommandHandler
{
    public function __construct(private Transportation $transportation, private EventBusInterface $eventBus){}

    public function __invoke(ReplaceCarsCommand $command)
    {
        $this->transportation->replaceEvPool($command->getCarDtos());
        $this->eventBus->dispatch(new EvPoolReplacedEvent());
    }
}
