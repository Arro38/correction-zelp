<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $restaurantRepository;

    public function __construct(UserRepository $userRepository, RestaurantRepository $restaurantRepository)
    {
        $this->userRepository = $userRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $clients = $this->userRepository->findByRole('ROLE_CLIENT');
        $restaurants = $this->restaurantRepository->findAll();

        foreach ($restaurants as $r) {
            //Créer 5 avis par restaurant
            for ($i = 0; $i < 5; $i++) {
                $faker = Faker\Factory::create('fr_FR');
                $avis = new Avis();
                $avis->setMessage($faker->text);
                $avis->setRating($faker->numberBetween(1, 5));
                $avis->setUser($clients[rand(0, count($clients) - 1)]);
                $avis->setRestaurant($r);
                $manager->persist($avis);
            }
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
