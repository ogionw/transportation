<?php
namespace App\Presentation\Test;

use App\Presentation\Response\EndpointResponses\Locate\LocateResponseStrategy;

class HandlerCollection
{
    public function __construct(iterable $handlers = [])
    {
        foreach ($handlers as $handler){
            echo get_class($handler)."-".($handler instanceof LocateResponseStrategy)."<br/>";
        }
    }
}