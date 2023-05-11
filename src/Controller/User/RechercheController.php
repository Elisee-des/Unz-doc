<?php

namespace App\Controller\User;

use App\Form\User\RechercheAnneeType;
use App\Form\User\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/recherche', name: 'app_user_recherche_')]
class RechercheController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(RechercheType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
        }

        return $this->render('user/recherche/index.html.twig', [
            'formRecherche' => $form->createView(),
        ]);
    }
}
