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
        $vids = [
            "https://www.youtube.com/watch?v=R2Cp1RumorU",
            "https://youtu.be/mBB7CznvSPQ",
            "https://www.dailymotion.com/video/xnltqb",
            "https://dai.ly/xnltrc",
        ];
        for ($i = 0; $i <= 6; $i++) {
            $figure = $figures[rand(0, 9)];
            $video = new Video();
            $url = $vids[rand(0, 3)];
            if (str_contains($url, "dailymotion") || str_contains($url, "dai.ly")) {
                $platform = "dailymotion";
                $url = str_replace(["https://www.dailymotion.com/video/", "https://dai.ly/"],'https://www.dailymotion.com/embed/video/', $url);
            } else {
                $platform = "youtube";
                $url = str_replace(["https://www.youtube.com/watch?v=", "https://youtu.be/"],'https://www.youtube.com/embed/', $url);
            }
            $video->setUrl($url)
                ->setPlatform($platform)
                ->setFigure($figure);

            $manager->persist($video);
        }
        $manager->flush();
    }
}
