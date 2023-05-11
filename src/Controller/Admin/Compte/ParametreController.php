<?php

namespace App\Controller\Admin\Compte;

use App\Form\Admin\Compte\EditionImageType;
use App\Form\Admin\Compte\EditionMotSecretType;
use App\Form\Admin\Compte\EditionPasswordType;
use App\Form\Admin\Compte\EditionProfilType;
use App\Repository\UserRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/compte/parametre', name: 'app_admin_compte_parametre_')]
class ParametreController extends AbstractController
{
    #[Route('/edition-du-profil', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('admin/compte/parametre/index.html.twig', [
            'controller_name' => 'ParametreController',
        ]);
    }

    #[Route('/edition-du-profil/{id}', name: 'edition_du_profil')]
    public function editionProfil(Request $request, EntityManagerInterface $em): Response
    {
        /**
         * @var User
         */

        $user = $this->getUser();

        $form = $this->createForm(EditionProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez editer avec success les informations de votre profil'
            );

            return $this->redirectToRoute('app_admin_compte_profil');
            // return $this->redirectToRoute('app_admin_compte_parametre_edition_du_profil', ["id" => $user->getId()]);

        }

        return $this->render('admin/compte/parametre/editionProfil.html.twig', [
            'formEditionProfil' => $form->createView(),
        ]);
    }

    #[Route('/edition-image-de-profil/{id}', name: 'edition_image')]
    public function editionImage(Request $request, EntityManagerInterface $em, UploaderService $uploaderService, UserRepository $userRepository, $id): Response
    {

        $user = $userRepository->find($id);

        $form = $this->createForm(EditionImageType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("image")->getData();
            $imageNom = $image->getClientOriginalName();
            $imageNomNouveau = $uploaderService->uploader($image);
            $user->setPhoto($imageNomNouveau);
            $user->setPhotoNom($imageNom);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez editer avec success votre image de profil'
            );

            return $this->redirectToRoute('app_admin_compte_profil');
            // return $this->redirectToRoute('app_admin_compte_parametre_edition_du_profil', ["id" => $user->getId()]);

        }

        return $this->render('admin/compte/parametre/editionImage.html.twig', [
            'formEditionImage' => $form->createView(),
        ]);
    }

    #[Route('/edition-du-mot-de-passe/{id}', name: 'edition_password')]
    public function editionPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHash, UserRepository $userRepository, $id): Response
    {

        $user = $userRepository->find($id);

        $form = $this->createForm(EditionPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp1 = $request->get("edition_password")["password"]["first"];
            $mdp2 = $request->get("edition_password")["password"]["second"];
            if ($mdp1 == $mdp2) {
                $password = $passwordHash->hashPassword($user, $mdp1);
                $user->setPassword($password);
                $user->setSauvegardeDuMotDePasse($mdp1);

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifier avec success'
                );

                return $this->redirectToRoute('app_admin_compte_profil');
                // return $this->redirectToRoute('app_admin_compte_parametre_edition_du_profil', ["id" => $user->getId()]);

            }
        }

        return $this->render('admin/compte/parametre/editionPassword.html.twig', [
            'formEditionPassword' => $form->createView(),
        ]);
    }

    #[Route('/edition/edition-de-mon-mot-secret/{id}', name: 'edition_mot_secret')]
    public function editionMotSecret(Request $request, $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(EditionMotSecretType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $em->persist($user);
            $em->flush();

            $this->addFlash(
               'success',
               'Votre mot secret a été modifier avec success'
            );

            return $this->redirectToRoute('app_admin_compte_profil');

        }

        return $this->render('admin/compte/parametre/editionMotSecret.html.twig', [
            'formEditionMotSecret' => $form->createView(),
        ]);
    }
}
