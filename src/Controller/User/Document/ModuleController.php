<?php

namespace App\Controller\User\Document;

use App\Repository\AnneeRepository;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/module', name: 'app_user_document_module_')]
class ModuleController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'liste')]
    public function index($idFiliere, $idOption, $idDepartement, $idAnnee , LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
      AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository, ModuleRepository $moduleRepository): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $departement = $departementRepository->find($idDepartement);
        $modules = $moduleRepository->findAllModules($idAnnee);
        $licence = $licenceRepository->find($idLicence);


        return $this->render('user/document/module/index.html.twig', [
            "annee" => $annee,
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'modules' => $modules["moduleDeCetteAnnee"],
            "option" => $option

        ]);
    }
}
