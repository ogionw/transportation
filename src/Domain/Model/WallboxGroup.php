<?php

namespace App\Domain\Model;

use App\Domain\Exception\InvalidPeopleNumberException;
use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use DateTimeImmutable;

trait WallboxGroup
{
    private Location $location;

    /**
     * @throws InvalidPeopleNumberException
     */
    public function gather(int $id, int $people, Sidewalk $sidewalk): Group
    {
        $group = new Gang();
        $group->setId($id);
        $group->setPeople($people);
        $group->setCreatedAt(new DateTimeImmutable());
        $group->validatePeople();
        $group->setLocation($sidewalk);
        return $group;
    }


    public function board(Sidewalk $sidewalk, Vehicle $vehicle): void
    {
        /** @var Car $vehicle */
        $this->setCar($vehicle);
        $sidewalk->save($this);
        $vehicle->accept($this);
    }

    public function exitVehicle(Sidewalk $sidewalk): void
    {
        $vehicle = $this->getVehicle();
        $vehicle->removeGang($this);
        $sidewalk->delete($this);
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @throws InvalidPeopleNumberException
     */
    private function validatePeople(): void
    {
        $people = $this->getPeople();
        if ($people > 6 || $people < 0){
            throw new InvalidPeopleNumberException($people, $this->getId());
        }
    }

    public function getVehicle(): Vehicle
    {
        return $this->getCar();
    }
}