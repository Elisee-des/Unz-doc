<?php

namespace App\Controller\Admin;

use App\Entity\Examen;
use App\Entity\PropositionExamen;
use App\Repository\PropositionExamenRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/proposition/examen', name: 'app_admin_proposition_examen_')]
class PropositionExamenController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(PropositionExamenRepository $propositionExamenRepository): Response
    {
        $propsExamens = $propositionExamenRepository->findAll();

        return $this->render('admin/proposition_examen/index.html.twig', [
            'propsExamens' => $propsExamens
        ]);
    }

    #[Route('/detail/{idPropsExamen}', name: 'detail')]
    public function detail($idPropsExamen, PropositionExamenRepository $propositionExamenRepository): Response
    {
        $propsExamen = $propositionExamenRepository->find($idPropsExamen);

        return $this->render('admin/proposition_examen/detail.html.twig', [
            'propsExamen' => $propsExamen
        ]);
    }

    #[Route('/approbation/{idPropsExamen}', name: 'approbation')]
    public function approbation($idPropsExamen, PropositionExamenRepository $propositionExamenRepository, EntityManagerInterface $em): Response
    {
        $propsExamen = $propositionExamenRepository->find($idPropsExamen);
        $ppropsExamen = $propsExamen->getExamen();
        $nomPropsExamen = $propsExamen->getExamenNom();
        $modulePropsExamen = $propsExamen->getModule();
        $nomSession = $propsExamen->getNomSession();
        $dateCreation = new \DateTime();

        // dd($propsExamen);
        // dd($ppropsExamen, $nomPropsExamen, $modulePropsExamen);

        $propsExamen->setStatus(true);

        $examen = new Examen();
        $examen->setFichier($ppropsExamen);
        $examen->setFichierNom($nomPropsExamen);
        $examen->setNom($nomSession);
        $examen->setModule($modulePropsExamen);
        $examen->setTailleFichier('');
        $examen->setRemarque('Aucune remarque');
        $examen->setDateCreation($dateCreation);

        // dd($examen);
        $em->persist($examen);
        $em->remove($propsExamen);
        $em->flush();

        $this->addFlash(
           'success',
           "Merci pour la confirmation et la validation."
        );

        return $this->redirectToRoute('app_admin_proposition_examen_liste');
    }

    #[Route('/rejeter/{idPropsExamen}', name: 'rejeter')]
    public function rejeter($idPropsExamen, PropositionExamenRepository $propositionExamenRepository, EntityManagerInterface $em): Response
    {
        $propsExamen = $propositionExamenRepository->find($idPropsExamen);

        $propsExamen->setStatus(true);

        $em->remove($propsExamen);
        $em->flush();

        $this->addFlash(
           'success',
           "Vous avez supprimer avec success une proposition d'examen."
        );

        return $this->redirectToRoute('app_admin_proposition_examen_liste');
    }

    #[Route('/telechargement/{idPropExamen}', name: 'telecharger')]
    public function telechargementResumer($idPropExamen, PropositionExamenRepository $propositionExamenRepository): Response
    {
        $propExamen = $propositionExamenRepository->find($idPropExamen);
        $nouveauNomPropExamen = $propExamen->getExamen();
        $nomFichierPropsExamen = $propExamen->getExamenNom();

        $file_with_path = $this->getParameter("images_directory") . "/" . $nouveauNomPropExamen;
        $response = new BinaryFileResponse( $file_with_path );
        $response->headers->set ( 'Content-Type', 'text/plain' );
        $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nomFichierPropsExamen );
        return $response;
    }
}
