<?php

namespace App\Controller\User\Document;

use App\Entity\PropositionCorrection;
use App\Form\User\PropositionCorrectionType;
use App\Repository\AnneeRepository;
use App\Repository\CorrectionRepository;
use App\Repository\DepartementRepository;
use App\Repository\ExamenRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use App\Repository\PropositionExamenRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class PropositionCorrectionController extends AbstractController
{
    #[Route('/user/document/proposition/correction/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'app_user_document_proposition_correction')]
    public function index(Request $request, EntityManagerInterface $em, $idFiliere, $idOption, $idDepartement, $idAnnee, $idModule, LicenceRepository $licenceRepository,
    FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository,
     ModuleRepository $moduleRepository, ExamenRepository $examenRepository, CorrectionRepository $correctionRepository, 
     UploaderService $uploaderService): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);
        $examens = $examenRepository->findAllExamens($idModule);
        $corrections = $correctionRepository->findAllCorrections($idModule);
        $module = $moduleRepository->find($idModule);

        /**
         * @var User
         */
        $user = $this->getUser();
        $userNumero = $user->getNumero();
        $userNomPrenom = $user->getNomPrenom(); 

        $propoCorrection = new PropositionCorrection();

        $form = $this->createForm(PropositionCorrectionType::class, $propoCorrection);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $propCorrection = $form->get("correctionFichier")->getData();

            if($propCorrection == NULL)
            {
                $this->addFlash(
                    'error',
                    'Vous devez obligatoirement proposer un fichier.'
                 );
     
                 return $this->redirectToRoute('app_user_document_examen_liste', ["idDepartement" => $departement->getId(),
                 "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
                  "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
            }
        
            $vraiNomExamen = $propCorrection->getClientOriginalName();
            $nouveauNomFichier = $uploaderService->uploader($propCorrection);
            // dd($vraiNomExamen, $nouveauNomFichier);

            $propoCorrection->setNomPrenom($userNomPrenom);
            $propoCorrection->setNumero($userNumero);
            $propoCorrection->setStatus(false);
            $propoCorrection->setModule($module);
            $propoCorrection->setDateCreation(new \DateTime());
            $propoCorrection->setCorrection($nouveauNomFichier);
            $propoCorrection->setCorrectionNom($vraiNomExamen);

            $em->persist($propoCorrection);
            $em->flush();

            $this->addFlash(
               'success',
               'Merci de pour avoir proposé une correction.'
            );

            return $this->redirectToRoute('app_user_document_examen_liste', ["idDepartement" => $departement->getId(),
            "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
             "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
        }

        return $this->render('user/document/proposition_correction/index.html.twig', [
            "formPropositionCorrection" => $form->createView(),
            "annee" => $annee,
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'examens' => $examens["examensDeCeModule"],
            'corrections' => $corrections["correctionDeCeModule"],
            "option" => $option,
            "module" => $module
        ]);
    }

    #[Route('/telechargement-proposition-correction/{idPropositionCorrection}', name: 'app_user_document_telecharger_proposition_correction')]
    public function telechargement($idPropositionCorrection, PropositionExamenRepository $propositionExamenRepository): Response
    {
        $propositionCorrection = $propositionExamenRepository->find($idPropositionCorrection);
        $nouveauNomExamen = $propositionCorrection->getExamen();
        $nomFichierExamen = $propositionCorrection->getExamenNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomExamen;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierExamen );
        return $response;
    }
}
