<?php

namespace App\Domain\Model;

interface Location
{
    public function accept(Visitor $visitor): void;
}