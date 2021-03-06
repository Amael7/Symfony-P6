<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $firstNames = ["Stephane", "Gregoire", "Thomas", "Angelo", "David", "Abraham", "Pierre"];
        $lastNames = ["Montoro", "Boisseau", "Sammen", "Heaven", "Adams", "Lincoln", "Roderick"];
        for ($i = 0; $i <= 6; $i++) {
            $user = new User();
            $user->setFirstName($firstNames[$i])
                ->setLastName($lastNames[$i])
                ->setUsername($faker->name)
                ->setEmail($faker->safeEmail)
                ->setPassword($faker->sha256)
                ->setIsVerified(true)
                ->setPhoto("default-profil.png");

            $manager->persist($user);
        }
        $manager->flush();
    }
}
