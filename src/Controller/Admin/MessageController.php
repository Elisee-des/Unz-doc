<?php

namespace App\Controller\Admin;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/admin/message', name: 'app_admin_message')]
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messages
        ]);
    }
}
