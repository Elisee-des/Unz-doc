<?php

namespace App\Controller\Admin\Compte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/admin/compte/profil', name: 'app_admin_compte_profil')]
    public function index(): Response
    {
        return $this->render('admin/compte/profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
