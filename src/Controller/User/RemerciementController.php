<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/remerciement', name: 'app_user_remerciement_')]
class RemerciementController extends AbstractController
{
    #[Route('/user/remerciement', name: 'liste')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            if ($user->isRemerciement() == true) {
                $etudiants[] = $user;
            }
        }

        return $this->render('user/remerciement/index.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('user/remerciement/detail.html.twig', [
            "user" => $user
        ]);
    }
}
