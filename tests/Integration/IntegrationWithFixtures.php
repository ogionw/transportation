<?php

namespace App\Tests\Integration;

use App\Application\Cqrs\CommandBusInterface;
use App\Infrastructure\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationWithFixtures extends KernelTestCase
{
    protected EntityManager $entityManager;
    protected ?CommandBusInterface $commandBus;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    protected function resetDb(): void
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([new AppFixtures()]);
    }
}
