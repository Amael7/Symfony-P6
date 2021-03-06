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
        $figuresAll = $manager->getRepository(Figure::class)->findAll();

        return $this->render('figure/index.html.twig', [
            'figures' => $figures,
            'figuresAll' => $figuresAll,
        ]);
    }

    // Get the 10 next Figures in the database and create a Twig file with them that will be displayed via Javascript
    #[Route('/figures/{start}', name: 'loadFigures')]
    public function loadFigures(ManagerRegistry $manager, $start = 10)
    {
        // Get 10 Figures from the start position
        $figures = $manager->getRepository(Figure::class)->findBy([], [], 10, $start);

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
            // on r??cup??re les images transmises
            $images = $form->get('images')->getData();
            if (count($images) >= 1) {
                // On boucle sur les images
                foreach($images as $image){
                    if ($image == "default_image.jpeg") {
                        $fileName = $image;
                    } else {
                        // On g??n??re un nouveau nom de fichier
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
            // on r??cup??re les videos transmises
            $videos = $form->get('videos')->getData();
            if (isset($videos)) {
                // on stocke l'url de la video dans la DB
                $vid = new Video();
                if (str_contains($videos, "dailymotion") || str_contains($videos, "dai.ly")) {
                    $vid->setPlatform("dailymotion");
                    $url = str_replace(["https://www.dailymotion.com/video/", "https://dai.ly/"],'https://www.dailymotion.com/embed/video/', $videos);
                } else {
                    $vid->setPlatform("youtube");
                    $url = str_replace(["https://www.youtube.com/watch?v=", "https://youtu.be/"],'https://www.youtube.com/embed/', $videos);
                }
                $vid->setUrl($url);
                $figure->addVideo($vid);
            }

            $figure = $form->getData();
            $em = $manager->getManager();
            $em->persist($figure);
            $em->flush();

            $this->addFlash(
                'success',
                'Figure enregistr?? avec succ??s !'
            );

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
        if ($form->isSubmitted() && $form->isValid()) {
            // on r??cup??re les images transmises
            $images = $form->get('images')->getData();
            if (count($images) >= 1 AND reset($images) != "default_image.jpeg") {
                // On boucle sur les images
                foreach($images as $image){
                    // On g??n??re un nouveau nom de fichier
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

            // on r??cup??re les videos transmises
            $videos = $form->get('videos')->getData();
            if (isset($videos)) {
                // on stocke l'url de la video dans la DB
                $vid = new Video();
                if (str_contains($videos, "dailymotion") || str_contains($videos, "dai.ly")) {
                    $vid->setPlatform("dailymotion");
                    $url = str_replace(["https://www.dailymotion.com/video/", "https://dai.ly/"],'https://www.dailymotion.com/embed/video/', $videos);
                } else {
                    $vid->setPlatform("youtube");
                    $url = str_replace(["https://www.youtube.com/watch?v=", "https://youtu.be/"],'https://www.youtube.com/embed/', $videos);
                }
                $vid->setUrl($url);
                $figure->addVideo($vid);
            }

            $em = $manager->getManager();
            $figure = $form->getData();
            $em->flush();

            $this->addFlash(
                'success',
                'Figure mis ?? jour avec succ??s !'
            );

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
        $messagesAll = array_reverse($manager->getRepository(Message::class)->findBy(['figure' => $id]));
        // $messages = $manager->getRepository(Message::class)->findBy(['figure' => $id], [], 10, 0);
        
        $messages = array_slice($messagesAll, 0, 10);
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

            $this->addFlash(
                'success',
                'Votre commentaire ?? ??t?? post?? !'
            );

            return $this->redirect($this->generateUrl('figure_show', ['id' => $id, 'slug' => $slug]));
        } 
        // Message Ending Block
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'user' => $user,
            'messages' => $messages,
            'messagesAll' => $messagesAll,
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
                'Ils n\'y a aucunes figure qui correspond ?? l\'id' . $id
            );
        }
        $em->remove($figure);
        $em->flush();

        $this->addFlash(
            'success',
            'La figure ?? ??t?? supprim?? avec succ??s !'
        );

        return $this->redirect($this->generateUrl('figures'));
    }

    #[Route("/delete/image/{id}", name: 'figure_delete_image', methods: ["DELETE"])]
    public function deleteImage(Image $image, ManagerRegistry $manager, Request $request) {
        $data = json_decode($request->getContent(), true);
        // On v??rifie si le token est valide
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

    #[Route("/delete/video/{id}", name: 'figure_delete_video', methods: ["DELETE"])]
    public function deleteVideo(Video $video, ManagerRegistry $manager, Request $request) {
        $data = json_decode($request->getContent(), true);
        // On v??rifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$video->getId(), $data['_token'])) {
            $em = $manager->getManager();
            $em->remove($video);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
