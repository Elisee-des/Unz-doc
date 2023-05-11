<?php

namespace App\Controller\Admin;

use App\Entity\Correction;
use App\Repository\PropositionCorrectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/proposition/correction', name: 'app_admin_proposition_correction_')]
class PropositionCorrectionController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(PropositionCorrectionRepository $propositionCorrectionRepository): Response
    {
        $propsCorrections = $propositionCorrectionRepository->findAll();

        return $this->render('admin/proposition_correction/index.html.twig', [
            'propsCorrections' => $propsCorrections
        ]);
    }

    #[Route('/detail/{idPropsCorrection}', name: 'detail')]
    public function detail($idPropsCorrection, PropositionCorrectionRepository $propositionCorrectionRepository): Response
    {
        $propsCorrection = $propositionCorrectionRepository->find($idPropsCorrection);

        return $this->render('admin/proposition_correction/detail.html.twig', [
            'propsCorrection' => $propsCorrection     
        ]);
    }

    #[Route('/approbation/{idPropsCorrection}', name: 'approbation')]
    public function approbation($idPropsCorrection, PropositionCorrectionRepository $propositionExamenRepository, EntityManagerInterface $em): Response
    {
        $propsCorrection = $propositionExamenRepository->find($idPropsCorrection);
        $ppropsCorrection = $propsCorrection->getCorrection();
        $nomPropsCorrection = $propsCorrection->getCorrectionNom();
        $modulePropsCorrection = $propsCorrection->getModule();
        $nomSession = $propsCorrection->getNomSession();
        $dateCreation = new \DateTime();

        $propsCorrection->setStatus(true);

        $correction = new Correction();
        $correction->setFichier($ppropsCorrection);
        $correction->setFichierNom($nomPropsCorrection);
        $correction->setNom($nomSession);
        $correction->setModule($modulePropsCorrection);
        $correction->setTailleFichier('');
        $correction->setRemarque('Aucune remarque');
        $correction->setDateCreation($dateCreation);

        $em->persist($correction);
        $em->remove($propsCorrection);
        $em->flush();

        $this->addFlash(
           'success',
           "Merci pour la confirmation et la validation."
        );

        return $this->redirectToRoute('app_admin_proposition_correction_liste');
    }

    #[Route('/telechargement-proposition-correction/{idPropCorrection}', name: 'telecharger')]
    public function telechargement($idPropCorrection, PropositionCorrectionRepository $propositionCorrectionRepository): Response
    {
        $propositionCorrection = $propositionCorrectionRepository->find($idPropCorrection);
        $nouveauNomPropsCorrection = $propositionCorrection->getCorrection();
        $nomFichierPropsCorrection = $propositionCorrection->getCorrectionNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomPropsCorrection;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierPropsCorrection );
        return $response;
    }
}
