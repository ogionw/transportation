<?php

namespace App\Presentation\Response\EndpointResponses\Locate;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupFoundCar implements LocateResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return is_null($e) && $response['carId'];
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['id' => $response['carId']], Response::HTTP_OK);
    }
}