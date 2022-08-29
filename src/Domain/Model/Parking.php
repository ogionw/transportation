<?php

namespace App\Domain\Model;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\EvPoolReplacedEvent;
use App\Domain\Event\JourneyEndedEvent;
use App\Domain\Exception\DuplicateCarIdException;
use App\Domain\Repository\VehicleRepositoryInterface;

class Parking implements Location
{
    /** @var Vehicle[] $vehicles */
    private array $vehicles = [];

    public function __construct(private VehicleRepositoryInterface $repo, private EventBusInterface $eventBus){}

    public function endJourney(Vehicle $vehicle): void
    {
        $this->eventBus->dispatch(new JourneyEndedEvent($vehicle->getId()));
    }

    /** @param Vehicle[]
     * @throws DuplicateCarIdException
     */
    public function replaceAllVehicles(array $vehicles)
    {
        $this->repo->deleteAllVehicles();
        $this->vehicles = [];
        foreach ($vehicles as $vehicle){
            $vehicle->validateSeats();
            $this->accept($vehicle);
        }
        $this->repo->saveAllVehicles($this->vehicles);

        $this->eventBus->dispatch(new EvPoolReplacedEvent());
    }

    public function remove(Vehicle $vehicle)
    {
        unset($this->vehicles[$vehicle->getId()]);
    }

    /**
     * @throws DuplicateCarIdException
     */
    public function accept(Visitor $vehicle): void
    {
        $id = $vehicle->getId();
        if(isset($this->vehicles[$id])){
            throw new DuplicateCarIdException($id);
        }
        $vehicle->setLocation($this);
        $this->vehicles[$id] = $vehicle;
    }

    public function getEvPool(): array
    {
        return $this->repo->findAll();
    }

    public function findMinimalCarForGroup(Group $group): ?Vehicle
    {
        $minVehicle = null;
        foreach ($this->vehicles as $vehicle){
            if($vehicle->matchesGroup($group)){
                return $vehicle;
            }
            $minVehicle = $vehicle->getSmaller($group, $minVehicle);
        }
        return $minVehicle;
    }

    public function depart(Road $road)
    {
        foreach ($this->vehicles as $vehicle){
            if(! $vehicle->isEmpty()){
                $vehicle->depart($road);
            }
        }
    }

    /** @param Vehicle[] $vehicles */
    public function setVehicles(array $vehicles)
    {
        $this->vehicles = $vehicles;
    }

    /** @return Vehicle[] */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    public function save(Vehicle $vehicle)
    {
        $this->repo->save($vehicle);
    }

    public function find(int $id): ?Vehicle
    {
        return $this->repo->find($id);
    }
}