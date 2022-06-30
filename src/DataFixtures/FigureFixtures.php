<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\FigureType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            FigureTypeFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $figureNames = [
            "Air to Fakie", "Big air", "Grabs", "Rodeoback / Rodeofront",
            "Underflip", "mute", "stalefish", "japan",
            "900", "nose grab"
        ];
        $figureDescriptions = [
            "Il s'agit d'une figure relativement simple, et plus précisément d'un saut sans rotation qui se fait généralement dans un pipe (un U). Le rider s'élance dans les airs et retombe dans le sens inverse.",
            "C'est l'une des épreuves les plus impressionnantes dans les compétitions de snow. Le rider s’élance à une vitesse folle avant de sauter sur une tremplin et de réaliser un maximum de tricks dans les airs. Le big air peut aussi faire référence au tremplin de neige duquel le snowboardeur s'élance avant de faire ses figures.",
            "Les grabs sont la base des figures freestyle en snowboard. C’est le fait d’attraper sa planche avec une ou deux mains pendant un saut. On en compte six de base : indy, mute, nose grab, melon, stalefish et tail grab.",
            "C'est une figure qui consiste à faire un salto arrière en y ajoutant une rotation d'un demi-tour. Le rodeo est back quand le snowboarder part de dos et front quand il part de face.",
            "Le frontside underflip 540 est une figure qui mêle un frontside 180 et un backflip. Ce trick peut paraître intimidant, mais il n'est pas si compliqué. Hormis le décollage, bien sûr. Ensuite, les mouvements peuvent s'enchaîner assez naturellement.",
            "saisie de la carre frontside de la planche entre les deux pieds avec la main avant",
            "saisie de la carre backside de la planche entre les deux pieds avec la main arrière",
            "saisie de l'avant de la planche, avec la main avant, du côté de la carre frontside",
            "deux tours et demi",
            "saisie de la partie avant de la planche, avec la main avant"
        ];
        $figureTypes = $manager->getRepository(FigureType::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        
        for ($i = 0; $i <= 9; $i++) {
            $user = $users[rand(0, 6)];
            $figure = new Figure();
            $now = new \DateTime();
            $figure->setName($figureNames[$i])
                ->setDescription($figureDescriptions[$i])
                ->setType($figureTypes[rand(0, 4)])
                ->setUser($user)
                ->setCreatedAt($now)
                ->setUpdatedAt($now);
            $manager->persist($figure);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
