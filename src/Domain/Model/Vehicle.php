<?php

namespace App\Domain\Model;

use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use Doctrine\Common\Collections\Collection;

interface Vehicle extends Visitor
{
    public function setId(int $id): Car;

    public function getId(): int;

    public function getSeats(): int;

    public function setSeats(int $seats): Car;

    /**
     * @return Collection<int, \App\Infrastructure\Entity\Gang>
     */
    public function getGroups(): Collection;

    /**
     * @return Collection<int, Gang>
     */
    public function getGangs(): Collection;

    public function addGang(Gang $gang): Car;

    public function removeGang(Gang $gang): Car;

    public function accept(Visitor $group);

    public function getFreeSeats(): int;

    public function depart(Road $road): void;

    public function park(Parking $parking);

    public function isEmpty(): bool;

    public function setLocation(Parking $param);
}