<?php

namespace App\Controller\User;

use App\Form\User\RechercheAnneeType;
use App\Form\User\RechercheType;
use App\Repository\AnneeRepository;
use App\Repository\CorrectionRepository;
use App\Repository\ExamenRepository;
use App\Repository\FiliereRepository;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/recherche', name: 'app_user_recherche_')]
class RechercheController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(Request $request, ExamenRepository $examenRepository,
    CorrectionRepository $correctionRepository, ModuleRepository $moduleRepository,
     AnneeRepository $anneeRepository, FiliereRepository $filiereRepository): Response
    {
        $form = $this->createForm(RechercheType::class);

        $form->handleRequest($request);
        $moduleId = 0;
        $anneeId = 0;
        $filiereId = 0;
        $module = $moduleRepository->find($moduleId);
        $annee = $anneeRepository->find($anneeId);
        $filiere = $filiereRepository->find($filiereId);
        $examens = $examenRepository->findAllExamens($moduleId);
        $corrections = $correctionRepository->findAllCorrections($moduleId);

        if ($form->isSubmitted() && $form->isValid()) {

            $moduleId =  $request->get('recherche')['module'];
            $module = $moduleRepository->find($moduleId);
            $anneeId = $module->getAnnee();
            $annee = $anneeRepository->find($anneeId);
            $filiereId = $module->getAnnee()->getOptionn()->getLicence()->getFiliere();
            $filiere = $filiereRepository->find($filiereId);

            $examens = $examenRepository->findAllExamens($moduleId);
            $corrections = $correctionRepository->findAllCorrections($moduleId);


        }

        return $this->render('user/recherche/index.html.twig', [
            'filiere' => $filiere,            
            'annee' => $annee,
            'module' => $module,
            'formRecherche' => $form->createView(),
            'examens' => $examens["examensDeCeModule"],
            'corrections' => $corrections["correctionDeCeModule"],
        ]);
    }
}
