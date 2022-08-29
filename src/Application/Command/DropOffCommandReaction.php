<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Domain\Exception\GroupNotFoundException;
use App\Domain\Model\Transportation;
use App\Presentation\Message\DropOffCommand;

final class DropOffCommandReaction implements CommandHandler
{
    public function __construct(private readonly Transportation $transportation){}

    /**
     * @throws GroupNotFoundException
     */
    public function __invoke(DropOffCommand $command)
    {
        $this->transportation->dropoff($command->getGroupId()->getId());
    }
}
