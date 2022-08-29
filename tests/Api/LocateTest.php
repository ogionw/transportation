<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Infrastructure\DataFixtures\MinimalSelectFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class LocateTest extends ApiTestCase
{
    public const URL = '/locate';

    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testPassengerReassignment(): void
    {
        //200 OK With the car as the payload when the group is assigned to a car.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = static::createClient()->request('PUT', '/evs', [
            'json' => [['id'=> 1, 'seats'=> 4], ['id'=> 2, 'seats'=> 4], ['id'=> 3, 'seats'=> 6]],
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $response = $this->getResponse([
            'body' => '{"id": 2}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['id'=>3]);
    }

    public function testGroupNotInCar(): void
    {
        //204 No Content When the group is waiting to be assigned to a car.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 2}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($response->getContent());
    }

    public function testGroupNotFound(): void
    {
        //404 Not Found When the group cannot be found.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 5}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonContains('{"id":null}');
    }

    public function testFailureInRequestFormat(): void
    {
        //400 Bad Request When there is a failure in the request format
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 1}',
            'headers' => ['content-type' => ['application/xml']],
        ]);
        $this->assertJsonContains(['exception' => 'Incorrect content type: "xml"']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testPayloadNotUnmarshalled(): void
    {
        //400 Bad Request the payload can't be unmarshalled.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => 'hello',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Syntax error']);
        $this->assertResponseStatusCodeSame(400);
    }

    private function getResponse(array $options): ResponseInterface|Response
    {
        return static::createClient()->request('POST', self::URL, $options);
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }
}
