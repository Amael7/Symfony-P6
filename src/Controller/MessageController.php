<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{

    // Get the 10 next messages in the database and create a Twig file with them that will be displayed via Javascript
    #[Route('/figures/{id}/messages/{start}', name: 'loadMessages')]
    public function loadMessage(ManagerRegistry $manager, $id, $start = 10)
    {
        $messagesAll = array_reverse($manager->getRepository(Message::class)->findBy(['figure' => $id]));
        
        // Get 10 messages from the start position
        $messages = array_slice($messagesAll, $start, 10);
        // $messages = $manager->getRepository(Message::class)->findBy(['figure' => $id], [], 10, $start);
        $user = $this->getUser();
        $figure = $manager->getRepository(Figure::class)->find($id);

        return $this->render('message/load_more_messages.html.twig', [
            'messages' => $messages,
            'user' => $user,
            'figure' => $figure,
        ]);
    }

    #[Route("/figures/{figureId}/{slug}/message/{messageId}/delete", name: 'message_delete')]
    public function delete(ManagerRegistry $manager, $figureId, $messageId, $slug) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $manager->getManager();
        $message = $manager->getRepository(Message::class)->find($messageId);
        if (!$message) {
            throw $this->createNotFoundException(
                'Ils n\'y a aucun message qui correspond à l\'id' . $messageId
            );
        }
        $em->remove($message);
        $em->flush();

        $this->addFlash(
            'success',
            'Votre commentaire à bien été supprimé !'
        );

        return $this->redirect($this->generateUrl('figure_show', ['id' => $figureId, 'slug' => $slug]));
    }
}
