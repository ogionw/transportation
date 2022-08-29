<?php

namespace App\Presentation\Response\EndpointResponses\Dropoff;

use App\Domain\Exception\GroupNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupNotFound implements DropoffResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e instanceof GroupNotFoundException;
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => $e->getMessage()], Response::HTTP_NOT_FOUND);
    }
}