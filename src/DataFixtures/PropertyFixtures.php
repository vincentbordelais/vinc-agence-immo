<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Property;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($p = 0; $p < 100; ++$p) {
            $property = new Property();
            $property->setTitle($faker->words(3, true))
                ->setDescription($faker->sentence())
                ->setSurface($faker->numberBetween(20, 400))
                ->setRooms($faker->numberBetween(1, 15))
                ->setBedrooms($faker->numberBetween(1, 10))
                ->setFloor($faker->numberBetween(0, 15))
                ->setPrice($faker->numberBetween(10000, 2000000))
                ->setHeat($faker->numberBetween(0, count(Property::HEAT) - 1))
                ->setCity($faker->city)
                ->setAddress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false);
            $manager->persist($property);
        }
        $manager->flush();
    }
}
