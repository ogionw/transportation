<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Infrastructure\DataFixtures\MinimalSelectFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class CarEndpointsTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testStatus(): void
    {
        static::createClient()->request('GET', '/status');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['message' => 'success']);
    }

    public function testHappyPath(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'json' => [["id"=> 3, "seats"=> 4], ["id"=> 2, "seats"=> 6]],
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'success']);
        $this->assertResponseStatusCodeSame(200);
    }

    public function testBadJson(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'body' => 'hello',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Syntax error']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testIncorrectContentType(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'json' => [["id"=> 3, "seats"=> 4], ["id"=> 2, "seats"=> 6]],
            'headers' => ['content-type' => ['application/xml']],
        ]);
        $this->assertJsonContains(['exception' => 'Incorrect content type: "xml"']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testIncorrectCar(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'json' => [["id"=> 3, "seats"=> 4], ["id"=> 2, "seats"=> 7]],
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Invalid seats number: 7 in car number 2']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testDuplicateCar(): void
    {
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'json' => [["id"=> 2, "seats"=> 4], ["id"=> 2, "seats"=> 6]],
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Duplicate Car ID: 2']);
        $this->assertResponseStatusCodeSame(400);
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }
}
