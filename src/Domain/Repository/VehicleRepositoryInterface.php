<?php

namespace App\Domain\Repository;

interface VehicleRepositoryInterface
{
    public function deleteAllVehicles();

    public function saveAllVehicles(array $vehicles);
}