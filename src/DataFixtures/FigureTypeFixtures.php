<?php

namespace App\DataFixtures;

use App\Entity\FigureType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FigureTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $Types = ["Flip", "Grab", "Rotation", "Jump", 'Autre'];
        foreach ($Types as $type) {
            $figureType = new FigureType();
            $figureType->setTitle($type);

            $manager->persist($figureType);
        }
        $manager->flush();
    }
}
