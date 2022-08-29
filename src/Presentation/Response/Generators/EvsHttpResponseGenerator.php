<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\EndpointResponses\Evs\EvsResponseStrategy;
use App\Presentation\Response\ResponseGenerator;

class EvsHttpResponseGenerator extends EndpointResponseGenerator implements ResponseGenerator
{
    protected function belongs(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof EvsResponseStrategy;
    }
}