<?php

namespace App\Controller\Admin;

use App\Entity\Correction;
use App\Form\Admin\Correction\AjoutType;
use App\Form\Admin\Correction\EditionType;
use App\Repository\AnneeRepository;
use App\Repository\CorrectionRepository;
use App\Repository\DepartementRepository;
use App\Repository\ExamenRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use App\Service\UploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/correction', name: 'app_admin_correction_')]
class CorrectionController extends AbstractController
{
    // #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'liste')]
    // public function index($idFiliere, $idOption, $idDepartement, $idAnnee, $idModule, LicenceRepository $licenceRepository,
    //  FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
    //   AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository,
    //   ModuleRepository $moduleRepository, ExamenRepository $examenRepository): Response
    // {
    //     $option = $optionRepository->find($idOption);
    //     $filiere = $filiereRepository->find($idFiliere);
    //     $annee = $anneeRepository->find($idAnnee);
    //     $departement = $departementRepository->find($idDepartement);
    //     $licence = $licenceRepository->find($idLicence);
    //     $examens = $examenRepository->findAllExamens($idModule);
    //     $module = $moduleRepository->find($idModule);

    //     return $this->render('admin/correction/index.html.twig', [
    //         "annee" => $annee,
    //         'licence' => $licence,
    //         'filiere' => $filiere,
    //         'departement' => $departement,
    //         'examens' => $examens["examensDeCeModule"],
    //         "option" => $option,
    //         "module" => $module

    //     ]);
    // }

    #[Route('/ajout/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'ajout')]
    public function ajout($idLicence, $idOption, $idAnnee, $idModule , Request $request, EntityManagerInterface $em, $idFiliere, FiliereRepository $filiereRepository,
     $idDepartement, DepartementRepository $departementRepository, LicenceRepository $licenceRepository, OptionRepository $optionRepository,
     AnneeRepository $anneeRepository, ModuleRepository $moduleRepository, UploaderService $uploaderService): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);
        $annee = $anneeRepository->find($idAnnee);
        $module = $moduleRepository->find($idModule);

        $correction = new Correction();
        $form = $this->createForm(AjoutType::class, $correction);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("fichierPdf")->getData();

            if ($fichier == '') {
                    $correction->setFichier("aucunFichier");
                    $correction->setFichierNom("aucunFichier");
                    $correction->setModule($module);
                    $correction->setDateCreation(new DateTime());
                    $em->persist($correction);
                    $em->flush();

                    $this->addFlash(
                    'success',
                    'Correction ajouté avec success.'
                    );

                    return $this->redirectToRoute('app_admin_examen_liste', ["idDepartement" => $departement->getId(),
                    "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(), "idAnnee" => $annee->getId(), "module"=> $module->getId()]);
            }
            else {
                $vraiNomFichier = $fichier->getClientOriginalName();
                $nouveauNomFichier = $uploaderService->uploader($fichier);
                $correction->setFichier($nouveauNomFichier);
                $correction->setFichierNom($vraiNomFichier);
                $correction->setModule($module);
                $correction->setDateCreation(new DateTime());
                $em->persist($correction);
                $em->flush();

                $this->addFlash(
                'success',
                'Examen ajouté avec success.'
                );

                return $this->redirectToRoute('app_admin_examen_liste', ["idDepartement" => $departement->getId(),
                "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
                "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
            }
            
        }

        return $this->render('admin/correction/ajout.html.twig', [
            "formCorrection" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement,
            "licence" => $licence,
            "option" => $option,
            "annee" => $annee,
            "module" => $module,
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}/{idCorrection}', name: 'edition')]
    public function edition($idDepartement, $idLicence, $idFiliere, $idOption, $idAnnee, $idModule, $idCorrection, AnneeRepository $anneeRepository, Request $request, EntityManagerInterface $em,
     licenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     OptionRepository $optionRepository, ModuleRepository $moduleRepository, CorrectionRepository $correctionRepository, UploaderService $uploaderService): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);
        $module = $moduleRepository->find($idModule);
        $correction = $correctionRepository->find($idCorrection);
        

        $form = $this->createForm(EditionType::class, $correction);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("fichierPdf")->getData();

            if ($fichier == '') {
                    // $examen = $examenRepository->find($idExamen);
                    $correction->setFichier("aucunFichier");
                    $correction->setFichierNom("aucunFichier");
                    $correction->setModule($module);
                    $correction->setDateCreation(new DateTime());
                    $em->persist($correction);
                    $em->flush();

                    $this->addFlash(
                    'success',
                    'Correction ajouté avec success.'
                    );

                    return $this->redirectToRoute('app_admin_examen_liste', ["idDepartement" => $departement->getId(),
                    "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
                     "idAnnee" => $annee->getId(), "idModule"=> $module->getId()]);
            }
            else {
                // $examen = $examenRepository->find($idExamen);
                $vraiNomFichier = $fichier->getClientOriginalName();
                $nouveauNomFichier = $uploaderService->uploader($fichier);
                $correction->setFichier($nouveauNomFichier);
                $correction->setFichierNom($vraiNomFichier);
                $correction->setModule($module);
                $correction->setDateCreation(new DateTime());
                $em->persist($correction);
                $em->flush();

                $this->addFlash(
                'success',
                'Correction ajouté avec success.'
                );

                return $this->redirectToRoute('app_admin_examen_liste', ["idDepartement" => $departement->getId(),
                "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(),
                "idAnnee" => $annee->getId(), "idModule"=> $module->getId(), "idCorrection"=> $correction->getId()]);
            }
            
        }

        return $this->render('admin/correction/edition.html.twig', [
            "formCorrection" => $form->createView(),
            "licence" => $licence,
            "filiere" => $filiere,
            "departement" => $departement,
            "annee" => $annee,
            "option" => $option,
            "module" => $module,
            "correction" => $correction
        ]);
    }

    #[Route('/suppression/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}/{idCorrection}', name: 'suppression')]
    public function suppression($idDepartement, $idFiliere, $idLicence, $idOption, $idAnnee, $idModule, $idCorrection, licenceRepository $licenceRepository,
     EntityManagerInterface $em, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     AnneeRepository $anneeRepository, OptionRepository $optionRepository, ModuleRepository $moduleRepository, CorrectionRepository $correctionRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);
        $module = $moduleRepository->find($idModule);
        $correction = $correctionRepository->find($idCorrection);


        $em->remove($correction);
        $em->flush();

        $this->addFlash(
           'success',
           'Examen supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_examen_liste', ["idDepartement" => $departement->getId(),
        "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId(),
         "idAnnee" => $annee->getId(), "idModule" => $module->getId(), "idCorrection"=> $correction->getId()]);
    }

    
    #[Route('/telechargement/{idCorrection}', name: 'telecharger')]
    public function telechargementResumer($idCorrection, CorrectionRepository $correctionRepository): Response
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
