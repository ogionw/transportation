<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Domain\Model\Transportation;
use App\Presentation\Message\RequestJourneyCommand;

final class RequestJourneyCommandReaction implements CommandHandler
{
    public function __construct(private readonly Transportation $transportation){}

    public function __invoke(RequestJourneyCommand $command)
    {
        $this->transportation->requestJourney(
            $command->getGroupId()->getId(),
            $command->getPeople()
        );
    }
}
