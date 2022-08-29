<?php

namespace App\Tests\Integration;

use App\Domain\Exception\DuplicateCarIdException;
use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use App\Presentation\Dto\CarDto;
use App\Presentation\Message\ReplaceCarsCommand;
use Doctrine\Common\Collections\ArrayCollection;

class ReplaceCarsCommandTest extends IntegrationWithFixtures
{
    public function testReplaceCars(): void
    {
        $this->resetDb();
        $carRepo = $this->entityManager->getRepository(Car::class);
        $gangRepo = $this->entityManager->getRepository(Gang::class);
        $this->assertNull($carRepo->find(4));
        $this->assertNotNull($carRepo->find(3));
        $this->assertSame(3, $gangRepo->find(3)->getCar()->getId(3));

        $collection = new ArrayCollection([new CarDto(4, 5)]);
        $command = new ReplaceCarsCommand($collection);

        $this->commandBus->dispatch($command);
        $this->assertSame(5, $carRepo->find(4)->getSeats());
        self::bootKernel();
        $this->assertNull($carRepo->find(3));
        $this->assertNull($gangRepo->find(3));
    }

    public function testDuplicateCars(): void
    {
        $this->resetDb();
        $collection = new ArrayCollection([new CarDto(4, 5), new CarDto(4, 6)]);
        $command = new ReplaceCarsCommand($collection);
        $this->expectException(DuplicateCarIdException::class);
        $this->commandBus->dispatch($command);
    }
}
