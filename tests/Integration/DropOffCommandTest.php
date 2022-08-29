<?php

namespace App\Tests\Integration;

use App\Domain\Exception\GroupNotFoundException;
use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use App\Presentation\Message\DropOffCommand;
use DateTimeImmutable;

class DropOffCommandTest extends IntegrationWithFixtures
{
    public function testRemoveGroup(): void
    {
        $this->resetDb();
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $group = $gangRepo->find(1);
        $this->assertSame(4, $group->getPeople());
        $this->commandBus->dispatch(new DropOffCommand(1));
        $this->assertNull($gangRepo->find(1));
    }

    public function testRemoveNonexistentGroup(): void
    {
        $this->resetDb();
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $this->assertNull($gangRepo->find(44));
        $this->expectException(GroupNotFoundException::class);
        $this->commandBus->dispatch(new DropOffCommand(44));
    }

    public function testPartialExit(): void
    {
        $this->resetDb();
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $carRepo = $this->entityManager->getRepository(Car::class);
        $group = $gangRepo->find(3);
        $carId = $group->getCar()->getId();
        $group->setPeople(2);
        $gangRepo->save($group);
        $group2 = (new Gang())->setCreatedAt(new DateTimeImmutable())->setId(4)->setPeople(4)->setCar($group->getCar());
        $gangRepo->save($group2);
        $this->assertSame(0, $carRepo->find($carId)->getFreeSeats());
        $this->commandBus->dispatch(new DropOffCommand(3));
        $this->assertSame(2, $carRepo->find($carId)->getFreeSeats());
    }
}
