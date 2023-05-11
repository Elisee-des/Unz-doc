<?php

namespace App\Controller\User;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/contact', name: 'app_user_contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(Request $request, MessageRepository $messageRepository,
     EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        /**
         * @var User
         */

        $userConnecter = $this->getUser();
        $userId = $userConnecter->getId();
        $messages = $messageRepository->findAllMessages($userId);
        $user = $userRepository->find($userId);

        if (isset($_POST["envoie"])) {
            
            $contenu = $request->get("contenu");
            $message = new Message();
            
            $message->setContenu($contenu);
            $message->setDateCreation(new \DateTime());
            $message->setUser($user);
            $em->persist($message);
            $em->flush();

            $this->addFlash(
                'success',
                'Message envoié avec success.'
            );

            return $this->redirectToRoute('app_user_contact_accueil');
        }

        return $this->render('user/contact/index.html.twig', [
            'messages' => $messages["messagesDeCeUtilisateur"],
        ]);
    }
}
