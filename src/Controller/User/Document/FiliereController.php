<?php

namespace App\Controller\User\Document;

use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/filiere', name: 'app_user_document_filiere_')]
class FiliereController extends AbstractController
{
    #[Route('/liste/{idDepartement}', name: 'liste')]
    public function index($idDepartement, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($idDepartement);
        $filieres = $filiereRepository->findAllFilieres($idDepartement);

        return $this->render('user/document/filiere/index.html.twig', [
            'filieres' => $filieres["filieresDeCeDepartement"],
            "departement" => $departement
        ]);
    }
}
