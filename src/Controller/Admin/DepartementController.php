<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use App\Form\Admin\Departement\AjoutType;
use App\Form\Admin\Departement\EditionType;
use App\Repository\DepartementRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/departement', name: 'app_admin_departement_')]
class DepartementController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(DepartementRepository $departementRepository): Response
    {
        return $this->render('admin/departement/index.html.twig', [
            'departements' => $departementRepository->findAll()
        ]);
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $departement = new Departement();
        $form = $this->createForm(AjoutType::class, $departement);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $departement->setDateCreation(new DateTime());
            $em->persist($departement);
            $em->flush();

            $this->addFlash(
               'success',
               'Un departement a été creé avec success.'
            );

            return $this->redirectToRoute('app_admin_departement_liste');
        }

        return $this->render('admin/departement/ajout.html.twig', [
            "formDepartement" => $form->createView()
        ]);
    }

    #[Route('/edition/{idDepartement}', name: 'edition')]
    public function edition($idDepartement, Request $request, EntityManagerInterface $em, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($idDepartement);

        $form = $this->createForm(EditionType::class, $departement);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $departement->setDateCreation(new DateTime());
            $em->persist($departement);
            $em->flush();

            $this->addFlash(
               'success',
               'Le departement a été modifier avec success.'
            );

            return $this->redirectToRoute('app_admin_departement_detail', ["idDepartement" => $departement->getId()]);
        }

        return $this->render('admin/departement/edition.html.twig', [
            "formDepartement" => $form->createView(),
            "departement" => $departement
        ]);
    }

    #[Route('/detail/{idDepartement}', name: 'detail')]
    public function detail($idDepartement, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($idDepartement);

        return $this->render('admin/departement/detail.html.twig', [
            'departement' => $departement
        ]);
    }

    #[Route('/suppression/{idDepartement}', name: 'suppression')]
    public function suppression($idDepartement, DepartementRepository $departementRepository, EntityManagerInterface $em): Response
    {
        $departement = $departementRepository->find($idDepartement);

        $em->remove($departement);
        $em->flush();

        $this->addFlash(
           'success',
           'Le departement a été supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_departement_liste');
    }
}
