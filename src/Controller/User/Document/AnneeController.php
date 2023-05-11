<?php

namespace App\Controller\User\Document;

use App\Repository\AnneeRepository;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/annee', name: 'app_user_document_annee_')]
class AnneeController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}/{idOption}', name: 'liste')]
    public function index($idFiliere, $idOption, $idDepartement, LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
      AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annees = $anneeRepository->findAllAnnees($idOption);
        $licence = $licenceRepository->find($idLicence);


        return $this->render('user/document/annee/index.html.twig', [
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'annees' => $annees["anneeDeCetteOpion"],
            "option" => $option

        ]);
    }
}
