<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\CommonResponseStrategy;
use App\Presentation\Response\EndpointResponses\Common\DefaultResponse;
use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\ResponseGenerator;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class EndpointResponseGenerator implements ResponseGenerator
{
    protected array $strategies = [];

    public function __construct(iterable $strategies){
        foreach ($strategies as $strategy){
            if($this->belongs($strategy) || $this->belongsCommon($strategy)){
                $this->strategies[] = $strategy;
            }
        }
    }

    public function generate(array $response = [], Exception $e = null): JsonResponse
    {
        foreach ($this->strategies as $strategy){
            if($strategy->matches($response, $e)){
                return $strategy->response($response, $e);
            }
        }
        return (new DefaultResponse())->response($response, $e);
    }

    abstract protected function belongs(ResponseStrategy $responseStrategy): bool;

    protected function belongsCommon(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof CommonResponseStrategy;
    }
}