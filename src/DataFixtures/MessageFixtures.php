<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FigureFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();
        $figures = $manager->getRepository(Figure::class)->findAll();
        for ($i = 0; $i <= 20; $i++) {
            $figure = $figures[rand(0, 9)];
            $user = $users[rand(0, 6)];
            $now = new \DateTime('now');
            $message = new Message();
            $message->setContent($faker->realText)
                ->setUser($user)
                ->setFigure($figure)
                ->setCreatedAt($now);

            $manager->persist($message);
        }
        $manager->flush();
    }
}
