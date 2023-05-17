<?php

namespace App\Controller\Admin\Roles;

use App\Entity\User;
use App\Form\Admin\Role\Admin\AjoutType;
use App\Form\Admin\Role\Admin\EditionPasswordType;
use App\Form\Admin\Role\Admin\EditionType;
use App\Form\Admin\Role\Editeur\AjoutFichierExcelType;
use App\Repository\UserRepository;
use App\Service\UploaderService;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/roles/admin', name: 'app_admin_roles_admin_')]
class AdminController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_ADMIN", $role)) {
                $admins[] = $user;
            }
        }

        return $this->render('admin/roles/admin/index.html.twig', [
            'admins' => $admins
        ]);
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em, UploaderService $uploaderService): Response
    {
        $admin = new User();

        $form = $this->createForm(AjoutType::class, $admin);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("ajout")["password"];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();

            $image = $form->get("image")->getData();


            if ($image == '') {
                $admin->setPhoto('rien');
                $admin->setPhotoNom('rien');
            }
            
            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $admin->setPhoto($nouveauNomImage);
                $admin->setPhotoNom($photoNom);
            }

            $admin->setPassword($mdp);
            $admin->setRemerciement(false);
            $admin->setRoles(["ROLE_ADMIN"]);
            $admin->setDateCreation(new DateTime());
            $admin->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);

            $em->persist($admin);
            $em->flush();

            $this->addFlash(
               'success',
               'Administrateur creé avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_admin_liste');
        }

        return $this->render('admin/roles/admin/ajout.html.twig', [
            "formAdmin" => $form->createView()
        ]);
    }

    #[Route('/edition/{idAdmin}', name: 'edition')]
    public function edition($idAdmin, Request $request, EntityManagerInterface $em, UploaderService $uploaderService, UserRepository $userRepository): Response
    {
        $admin = $userRepository->find($idAdmin);

        $form = $this->createForm(EditionType::class, $admin);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("edition")["password"];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            $image = $form->get("image")->getData();
            $role = $request->get("edition")["roles"][0];

            if ($image == '') {
                $admin->setPhoto('rien');
                $admin->setPhotoNom('rien');
            }

            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $admin->setPhoto($nouveauNomImage);
                $admin->setPhotoNom($photoNom);
            }

            $admin->setPassword($mdp);
            $admin->setRemerciement(false);
            $admin->setRoles([$role]);
            $admin->setDateCreation(new DateTime());
            $admin->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);


            $em->persist($admin);
            $em->flush();

            $this->addFlash(
               'success',
               'admin edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_admin_detail', ['idAdmin'=>$admin->getId()]);
        }

        return $this->render('admin/roles/admin/edition.html.twig', [
            "admin" => $admin,
            "formAdmin" => $form->createView(),
        ]);
    }

    #[Route('/edition/mot-de-passe/{idAdmin}', name: 'edition_password')]
    public function editionPassword($idAdmin, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHash, UserRepository $userRepository): Response
    {
        $admin = $userRepository->find($idAdmin);

        $form = $this->createForm(EditionPasswordType::class, $admin);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("edition_password")["password"]['first'];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            $password = $passwordHash->hashPassword($admin, $mdp);

            $admin->setPassword($password);
            $admin->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);

            $em->persist($admin);
            $em->flush();

            $this->addFlash(
               'success',
               'Mot de passe edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_admin_detail', ['idAdmin'=>$admin->getId()]);
        }

        return $this->render('admin/roles/admin/editionPassword.html.twig', [
            "admin" => $admin,
            "formPasswordAdmin" => $form->createView(),
        ]);
    }

    #[Route('/detail/{idAdmin}', name: 'detail')]
    public function detail($idAdmin, UserRepository $userRepository): Response
    {
        $admin = $userRepository->find($idAdmin);

        return $this->render('admin/roles/admin/detail.html.twig', [
            'admin' => $admin
        ]);
    }

    #[Route('/suppression/{idAdmin}', name: 'suppression')]
    public function suppresion($idAdmin, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $admin = $userRepository->find($idAdmin);

        $em->remove($admin);
        $em->flush();

        $this->addFlash(
           'success',
           'Admin supprimer avec success.'
        );

        return $this->redirectToRoute('app_admin_roles_admin_liste');
    }

    #[Route('/admin/roles/banir/admin/{idAdmin}', name: 'app_admin_roles_admin_restaurer')]
    public function banissement($idAdmin, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $admin = $userRepository->find($idAdmin);

        $admin->setRoles(["ROLE_BANI"]);

        $em->persist($admin);
        $em->flush();

        $this->addFlash(
           'success',
           "Vous venez de banir avec success ". $admin->getNomPrenom() . " avec success. Désormis il le sera impossible de se connecter a son compte UNZ-DOC."
        );

        return $this->redirectToRoute('app_admin_roles_admin_detail', ["idAdmin"=>$admin->getId()] );
    }

    #[Route('/fichier-excel-ajout', name: 'fichier_excel_ajout')]
    public function importationAdmin(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(AjoutFichierExcelType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get("fichier")->getData();

            $chemin = $fichier->getPathName();

            $reader = ReaderEntityFactory::createXLSXReader();

            //on lis le fichier
            $reader->open($chemin);
            //lecture de fichier
            $excelTabDonnee = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $excelTabDonnee[] = $row->toArray();
                }
            }

            //Maitenant importation des donnes
            for ($i = 0; $i < count($excelTabDonnee); $i++) {

                $admin = new User();

                $clairPassword = $excelTabDonnee[$i][3];
                $hashPassword = $userPasswordHasher->hashPassword($admin, $clairPassword);

                $admin
                    ->setRoles(["ROLE_ADMIN"])
                    ->setRemerciement(0)
                    ->setNomPrenom($excelTabDonnee[$i][0])
                    ->setNumero($excelTabDonnee[$i][1])
                    ->setEmail($excelTabDonnee[$i][2])
                    ->setPassword($hashPassword)
                    ->setSauvegardeDuMotDePasse($excelTabDonnee[$i][3])
                    ->setIne($excelTabDonnee[$i][4])
                    ->setMotSecret($excelTabDonnee[$i][5])
                    ->setFiliere($excelTabDonnee[$i][6])
                    ->setSpecialite($excelTabDonnee[$i][7])
                    ->setAnnee($excelTabDonnee[$i][8])
                    ;

                $em->persist($admin);
            }

            $this->addFlash(
                'success',
                'Fichier excel importer avec success'
            );

            $em->flush();

            return $this->redirectToRoute('app_admin_roles_admin_liste');
        }

        return $this->render('admin/roles/admin/ajoutFichier.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
