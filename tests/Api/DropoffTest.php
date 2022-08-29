<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Infrastructure\DataFixtures\AppFixtures;
use App\Infrastructure\DataFixtures\MinimalSelectFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DropoffTest extends ApiTestCase
{
    public const URL = '/dropoff';

    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testGroupUnregisteredFromSidewalk(): void
    {
        //200 OK or 204 No Content When the group is unregistered correctly.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 1}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    public function testGroupUnregisteredFromCar(): void
    {
        //200 OK or 204 No Content When the group is unregistered correctly.
        $this->setFixtures(new AppFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 1}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(204);
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
        $this->assertJsonContains('{"exception":"Group not found!"}');
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
