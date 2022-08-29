<?php

namespace App\Presentation\Controller;

use App\Application\Cqrs\CommandBusInterface;
use App\Application\Cqrs\QueryBusInterface;
use App\Presentation\Exception\IncorrectContentTypeException;
use App\Presentation\Message\DropOffCommand;
use App\Presentation\Message\LocateGroupQuery;
use App\Presentation\Message\RequestJourneyCommand;
use App\Presentation\Response\HttpResponseGeneratorFactory;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GroupController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private Serializer $serializer;
    private HttpResponseGeneratorFactory $respGenFactory;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, HttpResponseGeneratorFactory $factory)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->respGenFactory = $factory;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    #[Route('/journey', name: 'journey', methods: 'POST')]
    public function requestJourney(Request $request): Response
    {
        $generator = $this->respGenFactory->create('/journey');
        try {
            $this->validateContentType($request->getContentType());
            $command = $this->serializer->deserialize($request->getContent(), RequestJourneyCommand::class, JsonEncoder::FORMAT);
            $this->commandBus->dispatch($command);
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/dropoff', name: 'dropoff', methods: 'POST')]
    public function dropOff(Request $request): Response
    {
        $generator = $this->respGenFactory->create('/dropoff');
        try {
            $this->validateContentType($request->getContentType());
            $command = $this->serializer->deserialize($request->getContent(), DropOffCommand::class, JsonEncoder::FORMAT);
            $this->commandBus->dispatch($command);
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/locate', name: 'locate', methods: 'POST')]
    public function locateCarByGroup(Request $request): Response
    {
        $generator = $this->respGenFactory->create('/locate');
        try {
            $this->validateContentType($request->getContentType());
            $query = $this->serializer->deserialize($request->getContent(), LocateGroupQuery::class, JsonEncoder::FORMAT);
            $response = $this->queryBus->handle($query);
            return $generator->generate($response);
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    private function validateContentType(string $contentType)
    {
        if($contentType !== 'json'){
            throw new IncorrectContentTypeException($contentType);
        }
    }
}
