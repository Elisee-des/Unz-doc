<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Form\Admin\Annee\AjoutType;
use App\Form\Admin\Annee\EditionType;
use App\Repository\AnneeRepository;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\OptionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/annee', name: 'app_admin_annee_')]
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


        return $this->render('admin/annee/index.html.twig', [
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'annees' => $annees["anneeDeCetteOpion"],
            "option" => $option

        ]);
    }

    #[Route('/ajout/{idDepartement}/{idFiliere}/{idLicence}/{idOption}', name: 'ajout')]
    public function ajout($idLicence,$idOption , Request $request, EntityManagerInterface $em, $idFiliere, FiliereRepository $filiereRepository,
     $idDepartement, DepartementRepository $departementRepository, LicenceRepository $licenceRepository, OptionRepository $optionRepository ): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);

        $annee = new Annee();
        $form = $this->createForm(AjoutType::class, $annee);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $annee->setOptionn($option);
            $annee->setDateCreation(new DateTime());
            $em->persist($annee);
            $em->flush();

            $this->addFlash(
               'success',
               'Une année universitaire a été creé avec success.'
            );

            return $this->redirectToRoute('app_admin_annee_liste', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId()]);
        }

        return $this->render('admin/annee/ajout.html.twig', [
            "formAnnee" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement,
            "licence" => $licence,
            "option" => $option
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'edition')]
    public function edition($idDepartement, $idLicence, $idFiliere, $idOption, $idAnnee, AnneeRepository $anneeRepository, Request $request, EntityManagerInterface $em,
     licenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     OptionRepository $optionRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);

        $form = $this->createForm(EditionType::class, $annee);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $annee->setOptionn($option);
            $licence->setDateCreation(new DateTime());
            $em->persist($licence);
            $em->flush();

            $this->addFlash(
               'success',
               "Année universitaire a été modifier avec success."
            );

            return $this->redirectToRoute('app_admin_annee_detail', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId() ,"idAnnee" => $annee->getId()]);
        }

        return $this->render('admin/annee/edition.html.twig', [
            "formLicence" => $form->createView(),
            "licence" => $licence,
            "filiere" => $filiere,
            "departement" => $departement,
            "annee" => $annee,
            "option" => $option
        ]);
    }

    #[Route('/detail/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'detail')]
    public function detail($idDepartement, $idFiliere, $idLicence, $idOption, $idAnnee , AnneeRepository $anneeRepository, licenceRepository $licenceRepository,
     DepartementRepository $departementRepository, FiliereRepository $filiereRepository, OptionRepository $optionRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $departement = $departementRepository->find($idDepartement);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);


        return $this->render('admin/annee/detail.html.twig', [
            'departement' => $departement,
            'filiere' => $filiere,
            'licence' => $licence,
            'annee' => $annee,
            'option' => $option
        ]);
    }
    
    #[Route('/suppression/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'suppression')]
    public function suppression($idDepartement, $idFiliere, $idLicence, $idOption, $idAnnee, licenceRepository $licenceRepository,
     EntityManagerInterface $em, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     AnneeRepository $anneeRepository, OptionRepository $optionRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);

        $em->remove($annee);
        $em->flush();

        $this->addFlash(
           'success',
           'Année universitaire a été supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_annee_liste', ["idDepartement" => $departement->getId(),
        "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId(), "idAnnee" => $annee->getId()]);
    }
}
