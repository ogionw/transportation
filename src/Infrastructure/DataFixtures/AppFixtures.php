<?php

namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\Car;
use App\Infrastructure\Entity\Gang;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cars = [];
        $cars[1] = (new Car())->setId(1)->setSeats(4);
        $cars[2] = (new Car())->setId(2)->setSeats(5);
        $cars[3] = (new Car())->setId(3)->setSeats(6);
        foreach ($cars as $car){
            $manager->persist($car);
        }
        $manager->flush();

        $manager->persist((new Gang())->setId(1)->setPeople(4)->setCreatedAt(new DateTimeImmutable())->setCar($cars[1]));
        $manager->persist((new Gang())->setId(2)->setPeople(5)->setCreatedAt(new DateTimeImmutable())->setCar($cars[2]));
        $manager->persist((new Gang())->setId(3)->setPeople(6)->setCreatedAt(new DateTimeImmutable())->setCar($cars[3]));
        $manager->flush();
    }
}
