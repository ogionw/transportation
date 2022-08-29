<?php

namespace App\Domain\Model;

use App\Domain\Exception\DuplicateGroupIdException;
use App\Domain\Exception\GroupNotFoundException;
use App\Infrastructure\Entity\Car;
use App\Presentation\Dto\CarDto;
use Doctrine\Common\Collections\ArrayCollection;

class Transportation
{
    private Parking $parking;
    private Road $road;
    private Sidewalk $sidewalk;

    public function __construct(Parking $parking, Road $road, Sidewalk $sidewalk)
    {
        foreach ($parking->getEvPool() as $vehicle){
            $vehicle->getGangs()->isEmpty() ? $parking->accept($vehicle) : $road->accept($vehicle);
        }
        $this->parking = $parking;
        $this->road = $road;
        $this->sidewalk = $sidewalk;
    }

    public function replaceEvPool(ArrayCollection $carDtos)
    {
        foreach ($this->road->getVehicles() as $vehicle){
            foreach ($vehicle->getGroups() as $group){
                $group->exitVehicle($this->sidewalk);
            }
            $vehicle->park($this->parking);
        }
        $vehicles = [];
        /** @var \App\Presentation\Dto\CarDto $carDto */
        foreach ($carDtos as $carDto){
            $vehicles[] = (new Car())->setId($carDto->getCarId()->getId())->setSeats($carDto->getSeats());
        }
        $this->parking->replaceAllVehicles($vehicles);
    }

    public function requestJourney(int $id, int $people)
    {
        if($this->road->locateGroup($id)){
            throw new DuplicateGroupIdException($id);
        }
        $this->sidewalk->formGroup($id, $people);
    }

    public function dropoff(int $id)
    {
        if ($this->sidewalk->dissolveGroupById($id)) {
            return;
        }
        $group = $this->road->locateGroup($id);
        if (! $group) {
            throw new GroupNotFoundException();
        }
        $vehicle = $group->getVehicle();
        $group->exitVehicle($this->sidewalk);
        $vehicle->park($this->parking);
    }

    public function locateGroup(int $id): ?Group
    {
        $group = $this->road->locateGroup($id);
        if($group){
            return $group;
        }
        $group = $this->sidewalk->locateGroup($id);
        if($group){
            return $group;
        }
        return null;
    }

    public function run()
    {
        $this->sidewalk->share($this->parking, $this->road);
    }

    public function getParking(): Parking
    {
        return $this->parking;
    }
    public function getRoad(): Road
    {
        return $this->road;
    }
    public function getSidewalk(): Sidewalk
    {
        return $this->sidewalk;
    }

}