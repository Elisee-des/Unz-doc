<?php

namespace App\Controller\User\Document;

use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/licence', name: 'app_user_document_licence_')]
class LicenceController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}', name: 'liste')]
    public function index($idFiliere, $idDepartement, LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licences = $licenceRepository->findAllLicences($idFiliere);

        return $this->render('user/document/licence/index.html.twig', [
            'licences' => $licences["licencesDeCeDepartement"],
            'filiere' => $filiere,
            'departement' =>$departement
        ]);
    }
}
