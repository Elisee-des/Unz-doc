<?php

namespace App\Controller\Main;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeContactController extends AbstractController
{
    #[Route('/main/liste/contact', name: 'app_main_liste_contact')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getRoles()[0] != "ROLE_ETUDIANT" && $user->getRoles()[0] != "ROLE_EDITEUR") {
                $admins[] = $user;
            }
        }

        return $this->render('main/liste_contact/index.html.twig', [
            'admins' => $admins
        ]);
    }
}
