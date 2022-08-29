<?php

namespace App\Presentation\Response;

use App\Presentation\Response\Generators\DropoffHttpResponseGenerator;
use App\Presentation\Response\Generators\EvsHttpResponseGenerator;
use App\Presentation\Response\Generators\InvalidPathHttpResponseGenerator;
use App\Presentation\Response\Generators\JourneyHttpResponseGenerator;
use App\Presentation\Response\Generators\LocateHttpResponseGenerator;

class HttpResponseGeneratorFactory
{
    public const DROPOFF = '/dropoff';
    public const JOURNEY = '/journey';
    public const LOCATE = '/locate';
    public const EVS = '/evs';
    public const INVALID = '/invalid';

    public function __construct(private iterable $responses){}

    public function create(string $httpPath): ResponseGenerator
    {
        return match ($httpPath) {
            self::DROPOFF => new DropoffHttpResponseGenerator($this->responses),
            self::JOURNEY => new JourneyHttpResponseGenerator($this->responses),
            self::LOCATE => new LocateHttpResponseGenerator($this->responses),
            self::EVS => new EvsHttpResponseGenerator($this->responses),
            self::INVALID => new InvalidPathHttpResponseGenerator($this->responses)
        };
    }
}