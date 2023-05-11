<?php

namespace App\Controller\Admin;

use App\Entity\Filiere;
use App\Form\Admin\Filiere\AjoutType;
use App\Form\Admin\Filiere\EditionType;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/filiere', name: 'app_admin_filiere_')]
class FiliereController extends AbstractController
{
    #[Route('/liste/{idDepartement}', name: 'liste')]
    public function index($idDepartement, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($idDepartement);
        $filieres = $filiereRepository->findAllFilieres($idDepartement);

        return $this->render('admin/filiere/index.html.twig', [
            'filieres' => $filieres["filieresDeCeDepartement"],
            "departement" => $departement
        ]);
    }

    #[Route('/ajout/{idDepartement}', name: 'ajout')]
    public function ajout($idDepartement, Request $request, EntityManagerInterface $em, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($idDepartement);

        $filiere = new Filiere();
        $form = $this->createForm(AjoutType::class, $filiere);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $filiere->setDepartement($departement);
            $filiere->setDateCreation(new DateTime());
            $em->persist($filiere);
            $em->flush();

            $this->addFlash(
               'success',
               'Un filiere a été creé avec success.'
            );

            return $this->redirectToRoute('app_admin_filiere_liste', ["idDepartement" => $departement->getId()]);
        }

        return $this->render('admin/filiere/ajout.html.twig', [
            "formFiliere" => $form->createView(),
            "departement" => $departement
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}', name: 'edition')]
    public function edition($idFiliere, $idDepartement, Request $request, EntityManagerInterface $em,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);

        $form = $this->createForm(EditionType::class, $filiere);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $filiere->setDepartement($departement);
            $filiere->setDateCreation(new DateTime());
            $em->persist($filiere);
            $em->flush();

            $this->addFlash(
               'success',
               'Le filiere a été modifier avec success.'
            );

            return $this->redirectToRoute('app_admin_filiere_detail', ["idFiliere" => $filiere->getId(), "idDepartement"=> $departement->getId() ]);
        }

        return $this->render('admin/filiere/edition.html.twig', [
            "formFiliere" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement
        ]);
    }

    #[Route('/detail/{idDepartement}/{idFiliere}', name: 'detail')]
    public function detail($idFiliere, $idDepartement, FiliereRepository $filiereRepository, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);


        return $this->render('admin/filiere/detail.html.twig', [
            'filiere' => $filiere,
            'departement' => $departement
        ]);
    }
    
    #[Route('/suppression/{idDepartement}/{idFiliere}', name: 'suppression')]
    public function suppression($idFiliere, $idDepartement, filiereRepository $filiereRepository, EntityManagerInterface $em, DepartementRepository $departementRepository): Response
    {
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);

        $em->remove($filiere);
        $em->flush();

        $this->addFlash(
           'success',
           'La filiere a été supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_filiere_liste', ["idFiliere" => $filiere->getId(), "idDepartement"=> $departement->getId() ]);
    }
}
