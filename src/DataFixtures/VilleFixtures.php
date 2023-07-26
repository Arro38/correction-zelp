<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $ville = new Ville();

            $ville->setName($faker->city);
            // Remove space from postal code
            $postalCode = str_replace(' ', '', $faker->postcode);
            $ville->setCodePostal($postalCode);

            $manager->persist($ville);
        }
        $manager->flush();
    }
}
