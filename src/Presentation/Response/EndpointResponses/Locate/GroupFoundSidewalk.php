<?php

namespace App\Presentation\Response\EndpointResponses\Locate;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupFoundSidewalk implements LocateResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return is_null($e) && $response['id'] && is_null($response['carId']);
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['id' => null], Response::HTTP_NO_CONTENT);
    }
}