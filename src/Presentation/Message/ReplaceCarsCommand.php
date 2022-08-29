<?php
declare(strict_types=1);

namespace App\Presentation\Message;

use Doctrine\Common\Collections\ArrayCollection;

final class ReplaceCarsCommand implements Command
{
    public function __construct(private readonly ArrayCollection $carDtos){}

    public function getCarDtos(): ArrayCollection
    {
        return $this->carDtos;
    }
}
