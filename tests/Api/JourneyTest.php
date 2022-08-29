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

class JourneyTest extends ApiTestCase
{
    public const URL = '/journey';

    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testGroupRegisteredOnSidewalk(): void
    {
        //200 OK or 202 Accepted When the group is registered correctly.
        $this->setFixtures(new AppFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 5, "people": 4}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(202);
        $locate = static::createClient()->request('POST', '/locate', [
            'body' => '{"id": 5}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($locate->getContent());
    }

    public function testGroupRegisteredOnCar(): void
    {
        //200 OK or 202 Accepted When the group is registered correctly.
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 5, "people": 4}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(202);
        $locate = static::createClient()->request('POST', '/locate', [
            'body' => '{"id": 5}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['id'=>2]);
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

    public function testIncorrectPeopleNumber(): void
    {
        //400 Bad Request When incorrect number of people
        $this->setFixtures(new MinimalSelectFixtures());
        $response = $this->getResponse([
            'body' => '{"id": 5, "people": 7}',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Invalid people number: 7 in group number 5']);
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
