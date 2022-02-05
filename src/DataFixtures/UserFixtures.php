<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $firstNames = ["Stephane", "Gregoire", "Thomas", "Angelo", "David", "Abraham", "Pierre"];
        $lastNames = ["Montoro", "Boisseau", "Sammen", "Heaven", "Adams", "Lincoln", "Roderick"];
        for ($i = 0; $i <= 6; $i++) {
            $user = new User();
            $user->setFirstName($firstNames[$i])
                ->setLastName($lastNames[$i])
                ->setUsername($user->getFirstName() . '-' . $user->getLastName())
                ->setEmail($user->getFirstName() . '.' . $user->getLastName() . '@hotmail.com')
                ->setPassword("password")
                ->setPhoto("http://placehold.it/150x150")
                ->setVerifiedUser(true)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            $manager->persist($user);
        }
        $manager->flush();
    }
}
