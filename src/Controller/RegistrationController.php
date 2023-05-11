<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NouveauMotDePasseType;
use App\Form\RecuperationType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Service\UploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nomPrenom = $request->get("registration_form")["nomPrenom"];
            $email = $request->get("registration_form")["email"];
            $motSecret = $request->get("registration_form")["motSecret"];
            $ine = $request->get("registration_form")["ine"];
            $image = $form->get("image")->getData();
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            if ($image == '') {
                $user->setPhoto('rien');
                $user->setPhotoNom('rien');
            }

            else
            {
                $photoNom = $image->getClientOriginalName();
                $photo = $uploaderService->uploader($image);
                $user->setPhoto($photo);
                $user->setPhotoNom($photoNom);
            }

            
            $user->setNomPrenom($nomPrenom)
            ->setEmail($email)
            ->setMotSecret($motSecret)
            ->setIne($ine)
            ->setRoles(["ROLE_ETUDIANT"])
            ->setRemerciement(false)
            ->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse)
            ->setDateCreation(new \DateTime())
            ;
            
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                    )
                );
                
            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/récupération-du-mot-de-passe', name: 'app_recuperation_password')]
    public function recuperation(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RecuperationType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $numeroSaisi = $request->get("recuperation")["numero"];
            $motSecretSaisi = $request->get("recuperation")["motSecret"];
            $etudiantBD = $userRepository->findAll();

            foreach ($etudiantBD as $etudiant) {
                $numeroEtudiantBD = $etudiant->getNumero();
                $motSecretEtudiantBD = $etudiant->getMotSecret();

                if ($numeroEtudiantBD == $numeroSaisi && $motSecretEtudiantBD == $motSecretSaisi) {
                    $this->addFlash(
                       'success',
                       'Vos identifiant sont correct.'
                    );
                    return $this->redirectToRoute('app_nouveau_mot_de_passe');
                }
            }

            $this->addFlash(
               'success',
               'Votre numéro ou votre mot de passe est incorrect. Veuillez resseayez'
            );
        }

        return $this->render('registration/recuperation.html.twig', [
            'recuperationForm' => $form->createView(),
        ]);
    }

    #[Route('/nouveau-mot-de-passe', name: 'app_nouveau_mot_de_passe')]
    public function index(Request $request, EntityManagerInterface $em, UserRepository $userRepository, UserPasswordHasherInterface $passwordHash): Response
    {
        $form = $this->createForm(NouveauMotDePasseType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $nouveauPassword = $request->get('nouveau_mot_de_passe')["password"]["first"];

            $password = $passwordHash->hashPassword($nouveauPassword);

        }

        return $this->render('registration/nouveauMotDePasse.html.twig', [
            'nouveauMotDePasseForm' => $form->createView(),
        ]);
    }
}
