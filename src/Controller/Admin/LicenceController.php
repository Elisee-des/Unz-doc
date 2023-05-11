<?php

namespace App\Controller\Admin;

use App\Entity\Licence;
use App\Form\Admin\Licence\AjoutType;
use App\Form\Admin\Licence\EditionType;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/licence', name: 'app_admin_licence_')]
class LicenceController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}', name: 'liste')]
    public function index($idFiliere, $idDepartement, LicenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licences = $licenceRepository->findAllLicences($idFiliere);

        return $this->render('admin/licence/index.html.twig', [
            'licences' => $licences["licencesDeCeDepartement"],
            'filiere' => $filiere,
            'departement' =>$departement
        ]);
    }

    #[Route('/ajout/{idDepartement}/{idFiliere}', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em, $idFiliere, FiliereRepository $filiereRepository, $idDepartement, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);

        $licence = new Licence();
        $form = $this->createForm(AjoutType::class, $licence);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $licence->setFiliere($filiere);
            $licence->setDateCreation(new DateTime());
            $em->persist($licence);
            $em->flush();

            $this->addFlash(
               'success',
               'Un licence a été creé avec success.'
            );

            return $this->redirectToRoute('app_admin_licence_liste', ["idDepartement" => $departement->getId(), "idFiliere" => $filiere->getId()]);
        }

        return $this->render('admin/licence/ajout.html.twig', [
            "formLicence" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}/{idLicence}', name: 'edition')]
    public function edition($idDepartement, $idLicence, $idFiliere, Request $request, EntityManagerInterface $em,
     licenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);

        $form = $this->createForm(EditionType::class, $licence);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $licence->setFiliere($filiere);
            $licence->setDateCreation(new DateTime());
            $em->persist($licence);
            $em->flush();

            $this->addFlash(
               'success',
               'La licence a été modifier avec success.'
            );

            return $this->redirectToRoute('app_admin_licence_detail', ["idDepartement" => $departement->getId(), "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId()]);
        }

        return $this->render('admin/licence/edition.html.twig', [
            "formLicence" => $form->createView(),
            "licence" => $licence,
            "filiere" => $filiere,
            "departement" => $departement
        ]);
    }

    #[Route('/detail/{idDepartement}/{idFiliere}/{idLicence}', name: 'detail')]
    public function detail($idDepartement, $idFiliere, $idLicence, licenceRepository $licenceRepository,
     DepartementRepository $departementRepository, FiliereRepository $filiereRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $departement = $departementRepository->find($idDepartement);
        $filiere = $filiereRepository->find($idFiliere);

        return $this->render('admin/licence/detail.html.twig', [
            'departement' => $departement,
            'filiere' => $filiere,
            'licence' => $licence
        ]);
    }


    
    #[Route('/suppression/{idDepartement}/{idFiliere}/{idLicence}', name: 'suppression')]
    public function suppression($idDepartement, $idFiliere, $idLicence, licenceRepository $licenceRepository,
     EntityManagerInterface $em, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);

        $em->remove($licence);
        $em->flush();

        $this->addFlash(
           'success',
           'La licence a été supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_licence_liste', ["idDepartement" => $departement->getId(), "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId()]);
    }
}
