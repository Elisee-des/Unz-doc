<?php

namespace App\Controller\Admin;

use App\Form\Admin\AjoutRemerciementType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/remerciement', name: 'app_admin_remerciement_')]
class RemerciementController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            if ($user->isRemerciement() == true) {
                $etudiants[] = $user;
            }
        }

        return $this->render('admin/remerciement/index.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AjoutRemerciementType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $idUser = $request->get("ajout_remerciement")["user"];
            $description = $request->get("ajout_remerciement")["description"];

            $user = $userRepository->find($idUser);

            $user->setRemerciement(true);
            $user->setDescriptionRemerciement($description);
            $em->persist($user);
            $em->flush();

            $this->addFlash(
               'success',
               'Ajout reussie.'
            );
            return $this->redirectToRoute('app_admin_remerciement_liste');
        }

        return $this->render('admin/remerciement/ajout.html.twig', [
            "formRemerciement" => $form->createView(),
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('admin/remerciement/detail.html.twig', [
            "user" => $user
        ]);
    }
}
