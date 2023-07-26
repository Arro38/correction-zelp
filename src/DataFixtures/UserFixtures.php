<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $villeRepository;
    private $userPasswordHasher;

    public function __construct(UserRepository $userRepository, VilleRepository $villeRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->villeRepository = $villeRepository;
    }

    public function load(ObjectManager $manager): void
    {
        //Creer 10 clients
        for ($i = 0; $i < 10; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $user = new User();
            //check if username is unique
            while ($this->userRepository->findOneBy(['email' => $faker->email])) {
                $faker = Faker\Factory::create('fr_FR');
            }
            $user->setEmail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setRoles(['ROLE_CLIENT']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $faker->password
                )
            );
            $user->setVille($this->villeRepository->find(rand(1, 100)));
            $manager->persist($user);
        }

        //Creer 10 restaurateurs
        for ($i = 0; $i < 10; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $user = new User();
            //check if username is unique
            while ($this->userRepository->findOneBy(['email' => $faker->email])) {
                $faker = Faker\Factory::create('fr_FR');
            }
            $user->setEmail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setRoles(['ROLE_RESTAURATEUR']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $faker->password
                )
            );
            $user->setVille($this->villeRepository->find(rand(1, 100)));
            $manager->persist($user);
        }




        //Créer 1 Moderateur
        $moderateur = new User();
        $moderateur->setEmail('webmaster@etienne.re');
        $moderateur->setFirstname('Etienne');
        $moderateur->setLastname('Renaud');
        $moderateur->setRoles(['ROLE_MODERATEUR']);
        $moderateur->setPassword(
            $this->userPasswordHasher->hashPassword(
                $moderateur,
                '123456'
            )
        );
        $moderateur->setVille($this->villeRepository->find(1));
        $manager->persist($moderateur);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            VilleFixtures::class,
        ];
    }
}
