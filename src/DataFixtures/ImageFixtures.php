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
        foreach ($figures as $figure) {
            for ($i = 0; $i <= 2; $i++) {
                $image = new Image();
                $image->setUrl("https://loremflickr.com/640/360")
                    ->setFigure($figure);

                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
