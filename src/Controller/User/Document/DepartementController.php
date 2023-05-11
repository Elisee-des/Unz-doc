<?php

namespace App\Controller\User\Document;

use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/departement', name: 'app_user_document_departement_')]
class DepartementController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(DepartementRepository $departementRepository): Response
    {
        return $this->render('user/document/departement/index.html.twig', [
            'departements' => $departementRepository->findAll()
        ]);

    }
}
