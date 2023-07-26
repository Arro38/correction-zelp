<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $villeRepository;

    public function __construct(UserRepository $userRepository, VilleRepository $villeRepository)
    {
        $this->userRepository = $userRepository;
        $this->villeRepository = $villeRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $restaurateurs = $this->userRepository->findByRole('ROLE_RESTAURATEUR');

        foreach ($restaurateurs as $r) {
            //Créer 5 restaurants par restaurateur
            for ($i = 0; $i < 5; $i++) {
                $faker = Faker\Factory::create('fr_FR');
                $restaurant = new Restaurant();
                $restaurant->setName($faker->company);
                $restaurant->setDescription($faker->text);
                $restaurant->setVille($this->villeRepository->find(rand(1, 100)));
                $restaurant->setUser($r);
                $manager->persist($restaurant);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
