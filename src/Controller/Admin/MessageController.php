<?php

namespace App\Controller\Admin;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/message', name: 'app_admin_message_')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'liste')]
    public function index(MessageRepository $messageRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $messageRepository->findAll();

        $messages = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messages
        ]);
    }

    #[Route('/suppression/{idMessage}', name: 'suppression')]
    public function suppression($idMessage, MessageRepository $messageRepository, EntityManagerInterface $em): Response
    {
        $message = $messageRepository->find($idMessage);
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('app_admin_message_liste');
    }
}
