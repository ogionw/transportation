<?php

namespace App\Presentation\Response\EndpointResponses\Common;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IncorrectJson implements CommonResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e && $e->getMessage() === 'Syntax error';
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}