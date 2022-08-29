<?php

namespace App\Domain\Model;

use App\Domain\Repository\VehicleRepositoryInterface;
use App\Infrastructure\Entity\Car;

class Road implements Location
{
    /** @var Vehicle[] $vehicles */
    private array $vehicles = [];

    public function remove(Vehicle $vehicle): void
    {
        unset($this->vehicles[$vehicle->getId()]);
    }

    public function accept(Visitor $vehicle): void
    {
        $vehicle->setLocation($this);
        $this->vehicles[$vehicle->getId()] = $vehicle;
    }

    /** @return Vehicle[] */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    public function locateGroup(int $id): ?Group
    {
        /** @var Car $vehicle */
        foreach ($this->vehicles as $vehicle){
            foreach ($vehicle->getGangs() as $group){
                if($group->getId() === $id){
                    return $group;
                }
            }
        }
        return null;
    }
}