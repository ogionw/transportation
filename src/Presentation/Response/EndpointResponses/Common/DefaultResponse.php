<?php

namespace App\Presentation\Response\EndpointResponses\Common;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultResponse implements ResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return true;
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => 'cant find response'], Response::HTTP_BAD_REQUEST);
    }
}