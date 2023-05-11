<?php

namespace App\Controller\User\Document;

use App\Entity\PropositionExamen;
use App\Form\User\PropositionExamenType;
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
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class PropositionExamenController extends AbstractController
{
    #[Route('/user/document/proposition/examen/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'app_user_document_proposition_examen')]
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

        $propoExamen = new PropositionExamen();

        $form = $this->createForm(PropositionExamenType::class, $propoExamen);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $examen = $form->get("examenFichier")->getData();
            if($examen == NULL)
            {
                $this->addFlash(
                    'error',
                    'Vous devez obligatoirement proposer un fichier.'
                 );
     
                 return $this->redirectToRoute('app_user_document_examen_liste', ["idDepartement" => $departement->getId(),
                 "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
                  "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
            }

            $vraiNomExamen = $examen->getClientOriginalName();
            $nouveauNomFichier = $uploaderService->uploader($examen);

            $propoExamen->setNomPrenom($userNomPrenom);
            $propoExamen->setNumero($userNumero);
            $propoExamen->setStatus(false);
            $propoExamen->setModule($module);
            $propoExamen->setDateCreation(new \DateTime());
            $propoExamen->setExamen($nouveauNomFichier);
            $propoExamen->setExamenNom($vraiNomExamen);

            $em->persist($propoExamen);
            $em->flush();

            $this->addFlash(
               'success',
               'Merci de pour avoir proposé un examen.'
            );

            return $this->redirectToRoute('app_user_document_examen_liste', ["idDepartement" => $departement->getId(),
            "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
             "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
        }

        return $this->render('user/document/proposition_examen/index.html.twig', [
            "formPropositionExamen" => $form->createView(),
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

    #[Route('/telechargement-proposition-examen/{idPropositionExamen}', name: 'app_user_document_telecharger_proposition_examen')]
    public function telechargement($idPropositionExamen, PropositionExamenRepository $propositionExamenRepository): Response
    {
        $propositionExamen = $propositionExamenRepository->find($idPropositionExamen);
        $nouveauNomExamen = $propositionExamen->getExamen();
        $nomFichierExamen = $propositionExamen->getExamenNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomExamen;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierExamen );
        return $response;
    }
}
