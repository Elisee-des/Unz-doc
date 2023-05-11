<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\Admin\Option\AjoutType;
use App\Form\Admin\Option\EditionType;
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

#[Route('/admin/option', name: 'app_admin_option_')]
class OptionController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}', name: 'liste')]
    public function index($idFiliere, $idDepartement, LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository, OptionRepository $optionRepository, $idLicence): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $options = $optionRepository->findAlloptions($idLicence);
        $licence = $licenceRepository->find($idLicence);

        return $this->render('admin/option/index.html.twig', [
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'options' => $options["optionDeCetteLicence"],
        ]);
    }

    #[Route('/ajout/{idDepartement}/{idFiliere}/{idLicence}', name: 'ajout')]
    public function ajout($idLicence, Request $request, EntityManagerInterface $em, $idFiliere, FiliereRepository $filiereRepository,
     $idDepartement, DepartementRepository $departementRepository, LicenceRepository $licenceRepository, OptionRepository $optionRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);
        // $option = $optionRepository->find($idOption);

        $option = new Option();
        $form = $this->createForm(AjoutType::class, $option);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $option->setLicence($licence);
            $option->setDateCreation(new DateTime());
            $em->persist($option);
            $em->flush();

            $this->addFlash(
               'success',
               'Une option a été creé avec success.'
            );

            return $this->redirectToRoute('app_admin_option_liste', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId()]);
        }

        return $this->render('admin/option/ajout.html.twig', [
            "formOption" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement,
            "licence" => $licence,
            // "option" => $option
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}/{idLicence}/{idoption}', name: 'edition')]
    public function edition($idDepartement, $idLicence, $idFiliere, $idoption, optionRepository $optionRepository, Request $request, EntityManagerInterface $em,
     licenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $option = $optionRepository->find($idoption);

        $form = $this->createForm(EditionType::class, $option);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $option->setLicence($licence);
            $licence->setDateCreation(new DateTime());
            $em->persist($licence);
            $em->flush();

            $this->addFlash(
               'success',
               "Une option a été modifier avec success."
            );

            return $this->redirectToRoute('app_admin_option_detail', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId()]);
        }

        return $this->render('admin/option/edition.html.twig', [
            "formLicence" => $form->createView(),
            "licence" => $licence,
            "filiere" => $filiere,
            "departement" => $departement,
            "option" => $option
        ]);
    }

    #[Route('/detail/{idDepartement}/{idFiliere}/{idLicence}/{idOption}', name: 'detail')]
    public function detail($idDepartement, $idFiliere, $idLicence, $idOption, OptionRepository $optionRepository, licenceRepository $licenceRepository,
     DepartementRepository $departementRepository, FiliereRepository $filiereRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $departement = $departementRepository->find($idDepartement);
        $filiere = $filiereRepository->find($idFiliere);
        $option = $optionRepository->find($idOption);

        return $this->render('admin/option/detail.html.twig', [
            'departement' => $departement,
            'filiere' => $filiere,
            'licence' => $licence,
            'option' => $option
        ]);
    }

    #[Route('/suppression/{idDepartement}/{idFiliere}/{idLicence}/{idOption}', name: 'suppression')]
    public function suppression($idDepartement, $idFiliere, $idLicence, $idOption, licenceRepository $licenceRepository,
     EntityManagerInterface $em, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     OptionRepository $optionRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $option = $optionRepository->find($idOption);

        $em->remove($option);
        $em->flush();

        $this->addFlash(
           'success',
           'Option supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_option_liste', ["idDepartement" => $departement->getId(),
        "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idAnnee" => $option->getId()]);
    }
}
