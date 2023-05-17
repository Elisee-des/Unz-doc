<?php

namespace App\Controller\Admin\Roles;

use App\Entity\User;
use App\Form\Admin\Role\Etudiant\AjoutType;
use App\Form\Admin\Role\Etudiant\EditionType;
use App\Form\User\EditionPasswordType;
use App\Repository\UserRepository;
use App\Service\UploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/roles/etudiant', name: 'app_admin_roles_etudiant_')]
class EtudiantController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_ETUDIANT", $role)) {
                $datas[] = $user;
            }
        }

        $etudiants = $paginatorInterface->paginate(
            $datas,
            $request->query->getInt("page", 1),
            100
        );


        return $this->render('admin/roles/etudiant/index.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em, UploaderService $uploaderService): Response
    {
        $etudiant = new User();

        $form = $this->createForm(AjoutType::class, $etudiant);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("ajout")["password"];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();

            $image = $form->get("image")->getData();


            if ($image == '') {
                $etudiant->setPhoto('rien');
                $etudiant->setPhotoNom('rien');
            }
            
            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $etudiant->setPhoto($nouveauNomImage);
                $etudiant->setPhotoNom($photoNom);
            }

            $etudiant->setPassword($mdp);
            $etudiant->setRemerciement(false);
            $etudiant->setRoles(["ROLE_ETUDIANT"]);
            $etudiant->setDateCreation(new DateTime());
            $etudiant->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);


            $em->persist($etudiant);
            $em->flush();

            $this->addFlash(
               'success',
               'Etudiant creé avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_etudiant_liste');
        }

        return $this->render('admin/roles/etudiant/ajout.html.twig', [
            "formEtudiant" => $form->createView()
        ]);
    }

    #[Route('/edition/{idEtudiant}', name: 'edition')]
    public function edition($idEtudiant, Request $request, EntityManagerInterface $em, UploaderService $uploaderService, UserRepository $userRepository): Response
    {
        $etudiant = $userRepository->find($idEtudiant);

        $form = $this->createForm(EditionType::class, $etudiant);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $image = $form->get("image")->getData();
            $role = $request->get("edition")["roles"][0];

            if ($image == '') {
                $etudiant->setPhoto('rien');
                $etudiant->setPhotoNom('rien');
            }

            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $etudiant->setPhoto($nouveauNomImage);
                $etudiant->setPhotoNom($photoNom);
                
            }
            
            $etudiant->setRoles([$role]);
            $etudiant->setRemerciement(false);
            $etudiant->setDateCreation(new DateTime());

            $em->persist($etudiant);
            $em->flush();

            $this->addFlash(
               'success',
               'Etudiant edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_etudiant_detail', ['idEtudiant'=>$etudiant->getId()]);
        }

        return $this->render('admin/roles/etudiant/edition.html.twig', [
            "etudiant" => $etudiant,
            "formEtudiant" => $form->createView(),
        ]);
    }

    #[Route('/edition/mot-de-passe/{idEtudiant}', name: 'edition_password')]
    public function editionPassword($idEtudiant, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHash, UserRepository $userRepository): Response
    {
        $etudiant = $userRepository->find($idEtudiant);

        $form = $this->createForm(EditionPasswordType::class, $etudiant);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("edition_password")["password"]['first'];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            $password = $passwordHash->hashPassword($etudiant, $mdp);

            $etudiant->setPassword($password);
            $etudiant->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);

            $em->persist($etudiant);
            $em->flush();

            $this->addFlash(
               'success',
               'Mot de passe edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_etudiant_detail', ['idEtudiant'=>$etudiant->getId()]);
        }

        return $this->render('admin/roles/etudiant/editionPassword.html.twig', [
            "etudiant" => $etudiant,
            "formPasswordEtudiant" => $form->createView(),
        ]);
    }

    #[Route('/detail/{idEtudiant}', name: 'detail')]
    public function detail($idEtudiant, UserRepository $userRepository): Response
    {
        $etudiant = $userRepository->find($idEtudiant);

        return $this->render('admin/roles/etudiant/detail.html.twig', [
            'etudiant' => $etudiant
        ]);
    }

    #[Route('/suppression/{idEtudiant}', name: 'suppression')]
    public function suppresion($idEtudiant, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $etudiant = $userRepository->find($idEtudiant);

        $em->remove($etudiant);
        $em->flush();

        $this->addFlash(
           'success',
           'Etudiant supprimer avec success.'
        );

        return $this->redirectToRoute('app_admin_roles_etudiant_liste');
    }




}
