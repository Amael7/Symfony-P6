<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Figure;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/', name: 'figures')]
    public function index(ManagerRegistry $manager): Response
    {
        $figures = $manager->getRepository(Figure::class)->findAll();
        return $this->render('figure/index.html.twig', [
            'figures' => $figures
        ]);
    }

    #[Route('/figures/creer', name: 'figures_new')]
    public function create(ManagerRegistry $manager, Request $request) {
        $figure = new Figure();
        $form = $this->createFormBuilder($figure)
            ->add('Name', TextType::class)
            ->add('Description', TextType::class)
            ->add('Type', TextType::class)
            // ->add('Images', Image::class)
            // ->add('Videos', Video::class)
            ->add('Creer', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure = $form->getData();
            $em = $manager->getManager();
            // $em = $this->getDoctrine()->getManager();
            $em->persist($figure);
            $em->flush();
            echo 'Envoyé';
        }
        return $this->render('figure/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/figures/{id}", name: 'figures_show_{id}')]
    public function show(ManagerRegistry $manager, $id) {
        $figure = $manager->getRepository(Figure::class)->find($id);
        if (!$figure) {
            throw $this->createNotFoundException(
                'Aucun figure pour l\'id: ' . $id
            );
        }
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
        ]);
    }
}
