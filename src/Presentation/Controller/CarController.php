<?php

namespace App\Presentation\Controller;

use App\Application\Cqrs\CommandBusInterface;
use App\Domain\Repository\GroupRepositoryInterface;
use App\Presentation\Dto\CarDto;
use App\Presentation\Exception\IncorrectContentTypeException;
use App\Presentation\Message\ReplaceCarsCommand;
use App\Presentation\Response\HttpResponseGeneratorFactory;
use App\Presentation\Test\HandlerCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CarController extends AbstractController
{
    protected CommandBusInterface $commandBus;
    protected Serializer $serializer;
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->serializer = new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
    }

    #[Route('/status', name: 'status', methods: 'GET')]
    public function status(): Response
    {
        return new JsonResponse(['message'=>'success'],Response::HTTP_OK);
    }

    #[Route('/evs', name: 'evs', methods: 'PUT')]
    public function replacePool(Request $request, HttpResponseGeneratorFactory $factory): Response
    {
        $generator = $factory->create('/evs');
        try {
            $this->validateContentType($request->getContentType());
            $dtoArr = $this->serializer->deserialize($request->getContent(), CarDto::class.'[]', JsonEncoder::FORMAT);
            $adjustEvPoolCommand = new ReplaceCarsCommand(new ArrayCollection($dtoArr));
            $this->commandBus->dispatch($adjustEvPoolCommand);
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/test', name: 'test', methods: 'GET')]
    public function test(GroupRepositoryInterface $groupRepo, HandlerCollection $collection): Response
    {
        /** @var \App\Infrastructure\Entity\Gang $group */
        foreach ($groupRepo->getQueue() as $group){
            echo $group->getCreatedAt()->format("H:i:s")."<br/>";
        }
        return new Response('null',Response::HTTP_OK);
    }

    private function validateContentType(string $contentType)
    {
        if($contentType !== 'json'){
            throw new IncorrectContentTypeException($contentType);
        }
    }
}
