<?php

namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MinimalSelectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
        upon processing group 2 should take group 3 and get into car 3
        group 1 is unfortunately without a car
        group 4 gets into car 1
        car 2 is left without passengers
        */
        $cars = [];
        $cars[1] = (new Car())->setId(1)->setSeats(4);
        $cars[2] = (new Car())->setId(2)->setSeats(4);
        $cars[3] = (new Car())->setId(3)->setSeats(6);
        foreach ($cars as $car){
            $manager->persist($car);
        }
        $manager->flush();

        $manager->persist((new Gang())->setId(2)->setPeople(5)->setCreatedAt(new DateTimeImmutable("15 minutes ago")));
        $manager->persist((new Gang())->setId(1)->setPeople(6)->setCreatedAt(new DateTimeImmutable("10 minutes ago")));
        $manager->persist((new Gang())->setId(3)->setPeople(1)->setCreatedAt(new DateTimeImmutable("5 minutes ago")));
        $manager->persist((new Gang())->setId(4)->setPeople(4)->setCreatedAt(new DateTimeImmutable()));

        $manager->flush();
    }
}
