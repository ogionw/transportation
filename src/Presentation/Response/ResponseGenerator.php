<?php

namespace App\Presentation\Response;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ResponseGenerator
{
    public function generate(array $response = [], ?Exception $e = null): JsonResponse;
}