<?php

namespace App\Controller\Admin;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/message', name: 'app_admin_message_')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'liste')]
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

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
