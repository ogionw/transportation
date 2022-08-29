<?php

namespace App\Domain\Model;

use App\Domain\Exception\InsufficientSeatsNumberException;
use App\Domain\Exception\InvalidPeopleNumberException;
use App\Domain\Exception\JumpTheQueueException;
use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;

interface Group extends Visitor
{

    /**
     * @throws InvalidPeopleNumberException
     */
    public function gather(int $id, int $people, Sidewalk $sidewalk): Group;

    /**
     * @throws JumpTheQueueException
     * @throws InsufficientSeatsNumberException
     */
    public function board(Sidewalk $sidewalk, Vehicle $vehicle);

    public function exitVehicle(Sidewalk $sidewalk);

    public function getLocation(): Location;

    public function setLocation(Location $location): Gang;

    public function getVehicle(): Vehicle;

    public function getId(): ?int;

    public function setId(int $id): Gang;

    public function getPeople(): int;

    public function setPeople(int $people): Gang;

    public function getCreatedAt(): ?\DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): Gang;

    public function getCar(): ?Car;

    public function setCar(?Car $car): Gang;
}