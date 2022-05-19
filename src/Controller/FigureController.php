<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Figure;
use App\Entity\Message;
use App\Form\FigureType;
use App\Form\MessageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    #[Route('/', name: 'figures')]
    public function index(ManagerRegistry $manager): Response
    {
        $figures = $manager->getRepository(Figure::class)->findBy([], [], 10, 0);

        return $this->render('figure/index.html.twig', [
            'figures' => $figures,
        ]);
    }

    // Get the 10 next tricks in the database and create a Twig file with them that will be displayed via Javascript
    #[Route('/{start}', name: 'LoadFigures')]
    public function loadFigures(ManagerRegistry $manager, $start = 10)
    {
        // Get 15 Figures from the start position
        $figures = $manager->getRepository(Figure::class)->findBy([], [], 5, $start);

        return $this->render('figure/load_more_figures.html.twig', [
            'figures' => $figures,
        ]);
    }

    #[Route('/figure/new', name: 'figure_new')]
    public function create(ManagerRegistry $manager, Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $figure = new Figure();
        $user = $this->getUser();
        
        $form = $this->createForm(FigureType::class, $figure)
            ->add('user', HiddenType::class, [
                'empty_data' => $user,
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère les images transmises
            $images = $form->get('images')->getData();
            if (count($images) >= 1) {
                // On boucle sur les images
                foreach($images as $image){
                    if ($image == "default_image.jpeg") {
                        $fileName = $image;
                    } else {
                        // On génère un nouveau nom de fichier
                        $fileName = md5(uniqid()) . '.' . $image->guessExtension();
                        // on va copier le fichier dans le dossier uploads
                        $image->move(
                            $this->getParameter('images_directory'),
                            $fileName
                        );
                    }

                    // on stocke l'image dans la DB (son nom)
                    $img = new Image();
                    $img->setName($fileName);
                    $figure->addImage($img);
                }
            }

            // on récupère les videos transmises
            // $videos = $form->get('videos')->getData();
            // On boucle sur les videos
            // foreach($videos as $video){
            //     // on stocke l'url de la video dans la DB
            //     $vid = new Video();
            //     $vid->setUrl($video->getUrl());
            //     $figure->addVideo($vid);
            // }
                // on stocke l'url de la video dans la DB
                // $vid = new Video();
                // $vid->setUrl($videos);
                // $figure->addVideo($vid);

            $figure = $form->getData();
            $em = $manager->getManager();
            $em->persist($figure);
            $em->flush();
            return $this->redirect($this->generateUrl('figures'));
        }
        return $this->render('figure/new.html.twig', [
            'form' => $form->createView(),
            'figure' => $figure
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
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // on récupère les images transmises
            $images = $form->get('images')->getData();
            if (count($images) >= 1 AND reset($images) != "default_image.jpeg") {
                // On boucle sur les images
                foreach($images as $image){
                    // On génère un nouveau nom de fichier
                    $fileName = md5(uniqid()) . '.' . $image->guessExtension();
                    // on va copier le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );

                    // on stocke l'image dans la DB (son nom)
                    $img = new Image();
                    $img->setName($fileName);
                    $figure->addImage($img);
                }
            }

            $em = $manager->getManager();
            $figure = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('figure_show', ['id' => $id, 'slug' => $slug]));
        }
        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
            'figure' => $figure
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

    #[Route("/delete/image/{id}", name: 'figure_delete_image', methods: ["DELETE"])]
    public function deleteImage(Image $image, ManagerRegistry $manager, Request $request) {
        $data = json_decode($request->getContent(), true);
        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $fileName = $image->getName();
            unlink($this->getParameter('images_directory') . '/' . $fileName);

            $em = $manager->getManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
