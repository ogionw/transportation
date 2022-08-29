<?php

namespace App\Tests\Integration;

use App\Domain\Exception\DuplicateGroupIdException;
use App\Infrastructure\Entity\Gang;
use App\Presentation\Message\RequestJourneyCommand;

class RequestJourneyCommandTest extends IntegrationWithFixtures
{
    public function testAddGroup(): void
    {
        $this->resetDb();
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $this->assertNull($gangRepo->find(4));
        $this->commandBus->dispatch(new RequestJourneyCommand(4, 5));
        $this->assertSame(5, $gangRepo->find(4)->getPeople());
    }

    public function testAddExistingGroup(): void
    {
        $this->resetDb();
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $this->assertNotNull($gangRepo->find(3));
        $this->expectException(DuplicateGroupIdException::class);
        $this->commandBus->dispatch(new RequestJourneyCommand(3, 5));
    }
}
