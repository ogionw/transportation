<?php

namespace App\Tests\Integration;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\EvPoolReplacedEvent;
use App\Infrastructure\DataFixtures\MinimalSelectFixtures;
use App\Infrastructure\Entity\Gang;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EvPoolReplacedHandlingTest extends KernelTestCase
{
    protected EntityManager $entityManager;
    protected ?EventBusInterface $eventBus;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->eventBus = $container->get(EventBusInterface::class);
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testMinimalSelect(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $this->eventBus->dispatch(new EvPoolReplacedEvent());
        $groups = $this->entityManager->getRepository(Gang::class)->findAll();
        foreach ($groups as $group){
            if($group->getId() === 2 || $group->getId() === 3){
                $this->assertSame(3, $group->getCar()->getId());
            }
            if($group->getId() === 4){
                $this->assertSame(1, $group->getCar()->getId());
            }
            if($group->getId() === 1){
                $this->assertNull($group->getCar());
            }
        }
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }
}
