<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_main_')]
class PagePrincipalController extends AbstractController
{
    #[Route('', name: 'page_principal')]
    public function index(): Response
    {
        return $this->render('main/page_principal/index.html.twig', [
            'controller_name' => 'PagePrincipalController',
        ]);
    }
}
