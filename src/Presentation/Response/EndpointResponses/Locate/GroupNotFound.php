<?php

namespace App\Presentation\Response\EndpointResponses\Locate;

use App\Domain\Exception\GroupNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupNotFound implements LocateResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e instanceof GroupNotFoundException || is_null($e) && is_null($response['id']);
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['id' => null], Response::HTTP_NOT_FOUND);
    }
}