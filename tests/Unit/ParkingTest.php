<?php

namespace App\Tests\Unit;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Exception\DuplicateCarIdException;
use App\Domain\Model\Parking;
use App\Domain\Repository\VehicleRepositoryInterface;
use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use PHPUnit\Framework\TestCase;

class ParkingTest extends TestCase
{
    private Parking $sut;

    public function setUp(): void
    {
        $busMock = $this->createMock(EventBusInterface::class);
        $repoMock = $this->createMock(VehicleRepositoryInterface::class);
        $this->sut = new Parking($repoMock, $busMock);
        parent::setUp();
    }

    public function testAddVehicle(): void
    {
        $vehicle = (new Car())->setId(44)->setSeats(5);
        $this->sut->accept($vehicle);
        $this->assertArrayHasKey(44, $this->sut->getVehicles());
        $this->assertSame(5, $this->sut->getVehicles()[44]->getSeats());
    }

    public function testAddDuplicateVehicle(): void
    {
        $this->sut->accept((new Car())->setId(44)->setSeats(5));
        $this->expectException(DuplicateCarIdException::class);
        $this->expectExceptionMessage('Duplicate Car ID: 44');
        $this->sut->accept((new Car())->setId(44)->setSeats(6));
    }

    public function testFindMinimalCar(): void
    {
        $this->setVehicles([6,5,4,4,5,4,5]);
        $group = (new Gang())->setId(44)->setPeople(1);
        $vehicle = $this->sut->findMinimalCarForGroup($group);
        $this->assertSame(3, $vehicle->getId());
        $this->assertSame(4, $vehicle->getSeats());
    }

    public function testFindMinimalCarWhenPartlyOccupied(): void
    {
        $this->setVehicles([6,5,4,4,5,4,5],[2=>[4]]);
        $group = (new Gang())->setId(44)->setPeople(1);
        $vehicle = $this->sut->findMinimalCarForGroup($group);
        $this->assertSame(2, $vehicle->getId());
        $this->assertSame(1, $vehicle->getFreeSeats());
    }

    public function testFindMinimalCarNotFound(): void
    {
        $this->setVehicles([5,5,4,4,5,4,5]);
        $group = (new Gang())->setId(44)->setPeople(6);
        $vehicle = $this->sut->findMinimalCarForGroup($group);
        $this->assertNull($vehicle);
    }

    private function setVehicles(array $seats, array $groups = [])
    {
        foreach ($seats as $i=>$seatNum){
            $id = $i+1;
            $vehicle = (new Car())->setId($id)->setSeats($seatNum);
            if(isset($groups[$id])){
                foreach ($groups[$id] as $j => $peopleNum){
                    $gId = (int)($id+($j+1));
                    $group = (new Gang())->setId($gId)->setPeople($peopleNum)->setCar($vehicle);
                    $vehicle->addGang($group);
                }
            }
            $this->sut->accept($vehicle);
        }
    }
}
