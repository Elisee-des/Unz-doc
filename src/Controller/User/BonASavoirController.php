<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/bon/a/savoir', name: 'app_user_bon_a_savoir_')]
class BonASavoirController extends AbstractController
{
    #[Route('/', name: 'liste')]
    public function index(): Response
    {
        return $this->render('user/bon_a_savoir/index.html.twig', [
            'controller_name' => 'BonASavoirController',
        ]);
    }
}
