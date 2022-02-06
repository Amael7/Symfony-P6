<?php

namespace App\Controller;

use App\Entity\Figure;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/', name: 'figure')]
    public function index(ManagerRegistry $manager): Response
    {
        $figures = $manager->getRepository(Figure::class)->findAll();

        return $this->render('figure/index.html.twig', [
            'controller_name' => 'FigureController',
            'figures' => $figures
        ]);
    }
}
