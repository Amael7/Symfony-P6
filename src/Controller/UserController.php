<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'user')]
    public function show(): Response
    {
        return $this->render('user/show.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
