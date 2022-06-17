<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Figure;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            FigureFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $figures = $manager->getRepository(Figure::class)->findAll();
        $images = [
                    "fig_1.jpg", "fig_2.jpeg", "fig_3.jpeg", "fig_4.jpeg", "fig_5.jpeg",
                    "fig_6.jpeg", "fig_7.jpg", "fig_8.jpeg", "fig_9.jpeg", "fig_10.jpeg",
                    "fig_11.jpeg","fig_12.jpeg","fig_13.jpeg",
                ];
        foreach ($figures as $figure) {
            for ($i = 0; $i <= 1; $i++) {
                $image = new Image();
                $image->setName($images[rand(0, 12)])
                    ->setFigure($figure);

                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
