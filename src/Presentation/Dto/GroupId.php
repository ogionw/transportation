<?php

namespace App\Presentation\Dto;

class GroupId
{
    public function __construct(private readonly int $id){}

    public function getId(): int
    {
        return $this->id;
    }
}