<?php

namespace App\Presentation\Response\EndpointResponses\Common;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool;
    public function response(array $response = [], ?Exception $e = null): JsonResponse;
}