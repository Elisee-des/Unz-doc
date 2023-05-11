<?php

namespace App\Controller\User\Document;

use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/option', name: 'app_user_document_option_')]
class OptionController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}', name: 'liste')]
    public function index($idFiliere, $idDepartement, LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
      OptionRepository $optionRepository, $idLicence): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $options = $optionRepository->findAlloptions($idLicence);
        $licence = $licenceRepository->find($idLicence);

        return $this->render('user/document/option/index.html.twig', [
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'options' => $options["optionDeCetteLicence"],
        ]);
    }
}
