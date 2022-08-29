<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\EndpointResponses\Journey\JourneyResponseStrategy;
use App\Presentation\Response\ResponseGenerator;

class JourneyHttpResponseGenerator  extends EndpointResponseGenerator implements ResponseGenerator
{
    protected function belongs(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof JourneyResponseStrategy;
    }
}