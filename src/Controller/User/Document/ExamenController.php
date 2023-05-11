<?php

namespace App\Controller\User\Document;

use App\Repository\AnneeRepository;
use App\Repository\CorrectionRepository;
use App\Repository\DepartementRepository;
use App\Repository\ExamenRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use App\Repository\PropositionCorrectionRepository;
use App\Repository\PropositionExamenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/document/examen', name: 'app_user_document_examen_')]
class ExamenController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'liste')]
    public function index($idFiliere, $idOption, $idDepartement, $idAnnee, $idModule, LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
      AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository,
      ModuleRepository $moduleRepository, ExamenRepository $examenRepository, CorrectionRepository $correctionRepository, 
      PropositionExamenRepository $propositionExamenRepository, PropositionCorrectionRepository $propositionCorrectionRepository): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);
        $examens = $examenRepository->findAllExamens($idModule);
        $propositionExamens = $propositionExamenRepository->findAllExamens($idModule);
        $propositionCorrections = $propositionCorrectionRepository->findAllExamens($idModule);
        $corrections = $correctionRepository->findAllCorrections($idModule);
        $module = $moduleRepository->find($idModule);

        return $this->render('user/document/examen/index.html.twig', [
            "annee" => $annee,
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'examens' => $examens["examensDeCeModule"],
            'corrections' => $corrections["correctionDeCeModule"],
            'propositionExamens' => $propositionExamens["propositionExamensDeCeModule"],
            'propositionCorrections' => $propositionCorrections["propositionCorrectionsDeCeModule"],
            "option" => $option,
            "module" => $module,
            
        ]);
    }

    #[Route('/telechargement/{idExamen}', name: 'telecharger')]
    public function telechargementResumer($idExamen, ExamenRepository $examenRepository): Response
    {
        $examen = $examenRepository->find($idExamen);
        $nouveauNomExamen = $examen->getFichier();
        $nomFichierExamen = $examen->getFichierNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomExamen;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierExamen );
        return $response;
    }

    #[Route('/telechargement/{idCorrection}/corretion', name: 'telecharger_correction')]
    public function telechargemenCorrection($idCorrection, CorrectionRepository $correctionRepository): Response
    {
        $correction = $correctionRepository->find($idCorrection);
        $nouveauNomExamen = $correction->getFichier();
        $nomFichierExamen = $correction->getFichierNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomExamen;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierExamen );
        return $response;
    }
}
