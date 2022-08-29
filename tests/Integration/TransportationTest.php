<?php

namespace App\Tests\Integration;

use App\Domain\Model\Transportation;
use App\Infrastructure\DataFixtures\MinimalSelectFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TransportationTest extends KernelTestCase
{
    protected EntityManager $entityManager;

    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testLocations()
    {
        $this->setFixtures(new MinimalSelectFixtures());
        /** @var Transportation $sut */
        $sut = static::getContainer()->get(Transportation::class);
        $sut->run();
        $vehiclesOnRoad = $sut->getRoad()->getVehicles();
        $this->assertCount(2, $vehiclesOnRoad);
        $this->assertArrayHasKey(1, $vehiclesOnRoad);
        $this->assertArrayHasKey(3, $vehiclesOnRoad);

        $parked = $sut->getParking()->getVehicles();
        $this->assertCount(1, $parked);
        $this->assertArrayHasKey(2, $parked);

        $group = $sut->getSidewalk()->locateGroup(1);
        $this->assertNotNull($group);
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }
}
