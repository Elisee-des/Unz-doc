<?php

namespace App\Controller\Admin;

use App\Repository\PropositionCorrectionRepository;
use App\Repository\PropositionExamenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropositionController extends AbstractController
{
    #[Route('/admin/proposition', name: 'app_admin_proposition')]
    public function index(PropositionExamenRepository $propositionExamenRepository,
     PropositionCorrectionRepository $propositionCorrectionRepository): Response
    {
        return $this->render('admin/proposition/index.html.twig', [
            'PropsCorrections' => $propositionCorrectionRepository->findAll(),
            'PropsExamens' => $propositionExamenRepository->findAll(),
        ]);
    }
}
