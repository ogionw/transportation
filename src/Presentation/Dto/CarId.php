<?php

namespace App\Presentation\Dto;

class CarId
{
    public function __construct(private readonly int $id){}

    public function getId(): int
    {
        return $this->id;
    }
}