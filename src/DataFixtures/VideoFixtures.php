<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Figure;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VideoFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 0; $i <= 6; $i++) {
            $figure = $figures[rand(0, 9)];
            $video = new Video();
            $video->setUrl("https://youtu.be/JJy39dO_PPE")
                ->setFigure($figure);

            $manager->persist($video);
        }
        $manager->flush();
    }
}
