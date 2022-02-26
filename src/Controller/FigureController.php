<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/', name: 'figures')]
    public function index(ManagerRegistry $manager): Response
    {
        $figures = $manager->getRepository(Figure::class)->findAll();
        return $this->render('figure/index.html.twig', [
            'figures' => $figures,
        ]);
    }

    #[Route('/figure/new', name: 'figure_new')]
    public function create(ManagerRegistry $manager, Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $figure = new Figure();
        $user = $this->getUser();
        $now = new \DateTime();

        $form = $this->createFormBuilder($figure)
            ->add('name')
            ->add('description')
            ->add('type')
            ->add('user', HiddenType::class, [
                'empty_data' => $user,
            ])
            ->add('createdAt', HiddenType::class, [
                'empty_data' => $now->format('d/m/Y H:i:s'),
            ])
            ->add('updatedAt', HiddenType::class, [
                'empty_data' => $now->format('d/m/Y H:i:s'),
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure = $form->getData();
            $em = $manager->getManager();
            $em->persist($figure);
            $em->flush();
            return $this->redirect($this->generateUrl('figures'));
        }
        return $this->render('figure/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/figures/{id}/{slug}/edit', name: 'figure_edit')]
    public function update(ManagerRegistry $manager, Request $request, $id, $slug) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $figure = $manager->getRepository(Figure::class)->find($id);
        if (!$figure) {
            throw $this->createNotFoundException(
                'Il n\'y a aucune figures avec l\'id suivant: ' . $id
            );
        }
        $now = new \DateTime();
        $form = $this->createFormBuilder($figure)
            ->add('name')
            ->add('description')
            ->add('type')
            ->add('updatedAt', HiddenType::class, [
                'data' => $now->format('d/m/Y H:i:s'),
            ])
            ->add('save', SubmitType::class, array('label' => 'Editer'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $manager->getManager();
            $figure = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('figure_show', ['id' => $id, 'slug' => $slug]));
        }
        return $this->render('figure/edit.html.twig', [
                'form' => $form->createView()
        ]);
    }

    #[Route("/figures/{id}/{slug}", name: 'figure_show')]
    public function show(ManagerRegistry $manager, Request $request, $id, $slug) {
        $figure = $manager->getRepository(Figure::class)->find($id);
        $messages = $manager->getRepository(Message::class)->findBy(['figure' => $id]);
        $user = $this->getUser();
        if (!$figure) {
            throw $this->createNotFoundException(
                'Aucun figure pour l\'id: ' . $id
            );
        }

        // Message Starting Block 
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->add('user', HiddenType::class, [
                'empty_data' => $user,
            ])
            ->add('figure', HiddenType::class, [
                'empty_data' => $figure,
            ]);
            
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em = $manager->getManager();
            $em->persist($message);
            $em->flush();
            return $this->redirect($this->generateUrl('figure_show', ['id' => $id, 'slug' => $slug]));
        }
        // Message Ending Block


        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'user' => $user,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/figures/{id}/{slug}/delete", name: 'figure_delete')]
    public function delete(ManagerRegistry $manager, $id) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $manager->getManager();
        $figure = $manager->getRepository(Figure::class)->find($id);
        if (!$figure) {
            throw $this->createNotFoundException(
                'Ils n\'y a aucunes figure qui correspond à l\'id' . $id
            );
        }
        $em->remove($figure);
        $em->flush();
        return $this->redirect($this->generateUrl('figures'));
    }
}
