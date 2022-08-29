<?php

namespace App\Domain\Model;

use App\Domain\Exception\InsufficientSeatsNumberException;
use App\Domain\Exception\InvalidSeatsNumberException;
use App\Infrastructure\Entity\Gang;
use Doctrine\Common\Collections\Collection;

trait ElectricVehicle
{
    private Location $location;

    public function accept(Visitor $group): void
    {
        /** @var Gang $group */
        if($this->getFreeSeats() < $group->getPeople()){
            throw new InsufficientSeatsNumberException($this->getFreeSeats(), $this->getId());
        }
        $this->addGang($group);
        $group->setLocation($this);
    }

    public function getFreeSeats(): int
    {
        $seats = $this->getSeats();
        foreach ($this->getGangs() as $gang){
            $seats -= $gang->getPeople();
        }
        return $seats;
    }

    public function depart(Road $road): void
    {
        /** @var Parking $parking */
        $parking = $this->location;
        $parking->remove($this);
        $this->setLocation($road);
        $road->accept($this);
    }

    public function park(Parking $parking): void
    {
        if(! $this->isEmpty()) {
            return;
        }
        $road = $this->location;
        $road->remove($this);
        $this->setLocation($parking);
        $parking->accept($this);
        $parking->endJourney($this);
    }
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function matchesGroup(Group $group): bool
    {
        return $this->getFreeSeats() === $group->getPeople();
    }

    public function getSmaller(Group $group, ?Vehicle $vehicle): ?Vehicle
    {
        if($this->getFreeSeats() < $group->getPeople()){
            return $vehicle;
        } else if (is_null($vehicle)){
            return $this;
        }
        return $this->getFreeSeats() < $vehicle->getFreeSeats() ? $this : $vehicle;
    }

    /**
     * @throws InvalidSeatsNumberException
     */
    public function validateSeats(): void
    {
        $seats = $this->getSeats();
        if ($seats > 6 || $seats < 4){
            throw new InvalidSeatsNumberException($seats, $this->getId());
        }
    }

    public function isEmpty(): bool
    {
        return $this->getGangs()->isEmpty();
    }

    /**
     * @return Collection<int, \App\Infrastructure\Entity\Gang>
     */
    public function getGroups(): Collection
    {
        return $this->getGangs();
    }
}