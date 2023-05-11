<?php

namespace App\Controller\User;

use App\Form\User\EditionImageType;
use App\Form\User\EditionMotSecretType;
use App\Form\User\EditionPasswordType;
use App\Form\User\EditionProfilType;
use App\Form\User\EdittionProfilType;
use App\Repository\UserRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'app_user_')]
class ParametreController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        
        return $this->render('user/parametre/index.html.twig', [
            'controller_name' => 'ParametreController',
        ]);
    }

    #[Route('/parametre/modification-de-mon-image/{id}', name: 'parametre_edition_image')]
    public function image(Request $request, $id, UserRepository $userRepository, UploaderService $uploaderService, EntityManagerInterface $em): Response
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
               'Votre image a été modifier avec success'
            );

            return $this->redirectToRoute('app_user_dashboard');
        }

        return $this->render('user/parametre/editionImage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/parametre/modification-du-mot-de-passe/{id}', name: 'parametre_edition_password')]
    public function password(Request $request, EntityManagerInterface $em, UserRepository $userRepository, $id, UserPasswordHasherInterface $passwordHash): Response
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
            }

            $this->addFlash(
                'error',
                "Votre mot de passe n'est passe identique."
             );
 
 
             return $this->redirectToRoute('app_user_dashboard');
         }
        
        
        return $this->render('user/parametre/editionPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/parametre/modification-de-mon-profil/{id}', name: 'parametre_edition_profil')]
    public function editionProfil(Request $request, $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(EditionProfilType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($user);
            $em->flush();

            $this->addFlash(
               'success',
               'Votre profil a été modifier avec success'
            );

            return $this->redirectToRoute('app_user_dashboard');
        }

        return $this->render('user/parametre/editionProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/parametre/modification-de-mon-mot-secret/{id}', name: 'parametre_edition_mot_secret')]
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

            return $this->redirectToRoute('app_user_dashboard');
        }


        return $this->render('user/parametre/editionMotSecret.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
