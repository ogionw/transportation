<?php

namespace App\Presentation\Dto;

class CarDto
{
    private readonly CarId $carId;
    private readonly int $seats;
    public function __construct(int $id, int $seats)
    {
        $this->carId = new CarId($id);
        $this->seats = $seats;
    }

    public function getCarId(): CarId
    {
        return $this->carId;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }
}