<?php

namespace App\Controller\Admin;

use App\Entity\Examen;
use App\Repository\CorrectionRepository;
use App\Repository\DepartementRepository;
use App\Repository\ExamenRepository;
use App\Repository\FiliereRepository;
use App\Repository\MessageRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use App\Repository\PropositionCorrectionRepository;
use App\Repository\PropositionExamenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(UserRepository $userRepository, ExamenRepository $examenRepository,
    CorrectionRepository $correctionRepository, PropositionCorrectionRepository $propositionCorrectionRepository, 
    PropositionExamenRepository $propositionExamenRepository, DepartementRepository $departementRepository, 
    FiliereRepository $filiereRepository, ModuleRepository $moduleRepository, OptionRepository $optionRepository,
    MessageRepository $messageRepository): Response
    {
        $users = $userRepository->findAll();
        $filieres = $filiereRepository->findAll();
        $messages = $messageRepository->findBy([], ["id"=>"DESC"], 10);
        $examens = $examenRepository->findBy([], ["id"=>"DESC"], 10);
        $departements = $departementRepository->findBy([], ["id"=>"DESC"], 10);
        $corrections = $correctionRepository->findBy([], ["id"=>"DESC"], 10);
        $propoCorrections = $propositionCorrectionRepository->findBy([], ["id"=>"DESC"], 10);
        $propoExamens = $propositionExamenRepository->findBy([], ["id"=>"DESC"], 10);
        $cptSuperAdmin = 0;
        $cptAdmin = 0;
        $cptEditeur = 0;
        $cptEtudiant = 0;
        $cptUser = $userRepository->totalUsers();
        $cptExamen = $examenRepository->totalExamens()["totalExamens"];
        $cptCorrection = $correctionRepository->totalCorrections()["totalCorrections"];
        $cptPropositionCorrection = $propositionCorrectionRepository->totalPropoCorrections()["totalPropoCorrections"];
        $cptPropositionExamen = $propositionExamenRepository->totalPropoExamens()["totalPropoExamens"];
        $cptDepartement = $departementRepository->totalDepartements()["totalDepartements"];
        $cptFiliere = $filiereRepository->totalFilieres()["totalFilieres"];
        $cptModule = $moduleRepository->totalModules()["totalModules"];
        $cptOption = $optionRepository->totalOptions()["totalOptions"];
        $cptMessage = $messageRepository->totalMessages()["totalMessages"];

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_SUPER_ADMIN", $role)) {
                $cptSuperAdmin = $cptSuperAdmin + 1;
            }
        }

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_ADMIN", $role)) {
                $cptAdmin = $cptAdmin + 1;
            }
        }

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_EDITEUR", $role)) {
                $cptEditeur = $cptEditeur + 1;
            }
        }

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_ETUDIANT", $role)) {
                $cptEtudiant = $cptEtudiant + 1;
            }
        }

        // dd($cptAdmin, $cptUser, $cptSuperAdmin, $cptEditeur,
        //  $cptEtudiant, $cptExamen, $cptCorrection, $cptPropositionCorrection,
        //  $cptPropositionExamen, $cptDepartement, $cptFiliere, $cptModule, $cptOption
        // );

        return $this->render('admin/dashboard/index.html.twig', [
            'TSuperAdmin' => $cptSuperAdmin,
            'TAdmin' => $cptAdmin,
            'TEditeur' => $cptEditeur,
            'TEtudiant' => $cptEtudiant,
            'TUsers' => $cptUser,
            'TExamens' => $cptExamen,
            'TCorrections' => $cptCorrection,
            'TPropoCorrections' => $cptPropositionCorrection,
            'TPropoExamens' => $cptPropositionExamen,
            'TDepartements' => $cptDepartement,
            'TFilieres' => $cptFiliere,
            'TModules' => $cptModule,
            'TOptions' => $cptOption,
            'filieres' => $filieres,
            'messages' => $messages,
            'examens' => $examens,
            'departements' => $departements,
            'corrections' => $corrections,
            'propoCorrections' => $propoCorrections,
            'propoExamens' => $propoExamens,
        ]);
    }
}
