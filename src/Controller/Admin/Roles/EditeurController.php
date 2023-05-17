<?php

namespace App\Controller\Admin\Roles;

use App\Entity\User;
use App\Form\Admin\Role\Editeur\AjoutFichierExcelType;
use App\Form\Admin\Role\Editeur\AjoutType;
use App\Form\Admin\Role\Editeur\EditionPasswordType;
use App\Form\Admin\Role\Editeur\EditionType;
use App\Repository\UserRepository;
use App\Service\UploaderService;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/roles/editeur', name: 'app_admin_roles_editeur_')]
class EditeurController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_EDITEUR", $role)) {
                $datas[] = $user;
            }
        }

        $editeurs = $paginatorInterface->paginate(
            $datas,
            $request->query->getInt("page", 1),
            20
        );

        return $this->render('admin/roles/editeur/index.html.twig', [
            'editeurs' => $editeurs
        ]);
    }

    #[Route('/ajout', name: 'ajout')]
    public function ajout(Request $request, EntityManagerInterface $em, UploaderService $uploaderService): Response
    {
        $editeur = new User();

        $form = $this->createForm(AjoutType::class, $editeur);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("ajout")["password"];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();

            $image = $form->get("image")->getData();


            if ($image == '') {
                $editeur->setPhoto('rien');
                $editeur->setPhotoNom('rien');
            }
            
            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $editeur->setPhoto($nouveauNomImage);
                $editeur->setPhotoNom($photoNom);
            }

            $editeur->setPassword($mdp);
            $editeur->setRemerciement(false);
            $editeur->setRoles(["ROLE_EDITEUR"]);
            $editeur->setDateCreation(new DateTime());
            $editeur->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);


            $em->persist($editeur);
            $em->flush();

            $this->addFlash(
               'success',
               'Editeur creé avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_editeur_liste');
        }

        return $this->render('admin/roles/editeur/ajout.html.twig', [
            "formEditeur" => $form->createView()
        ]);
    }

    #[Route('/edition/mot-de-passe/{idEditeur}', name: 'edition_password')]
    public function editionPassword($idEditeur, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHash, UserRepository $userRepository): Response
    {
        $editeur = $userRepository->find($idEditeur);

        $form = $this->createForm(EditionPasswordType::class, $editeur);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("edition_password")["password"]['first'];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            $password = $passwordHash->hashPassword($editeur, $mdp);

            $editeur->setPassword($password);
            $editeur->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);

            $em->persist($editeur);
            $em->flush();

            $this->addFlash(
               'success',
               'Mot de passe edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_editeur_detail', ['idEditeur'=>$editeur->getId()]);
        }

        return $this->render('admin/roles/editeur/editionPassword.html.twig', [
            "editeur" => $editeur,
            "formPasswordEditeur" => $form->createView(),
        ]);
    }

    #[Route('/fichier-excel-ajout', name: 'fichier_excel_ajout')]
    public function importationEditeur(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher ): Response
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

                $editeur = new User();

                $clairPassword = $excelTabDonnee[$i][3];
                $hashPassword = $userPasswordHasher->hashPassword($editeur, $clairPassword);

                $editeur
                    ->setRoles(["ROLE_EDITEUR"])
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

                $em->persist($editeur);
            }

            $this->addFlash(
                'success',
                'Fichier excel importer avec success'
            );

            $em->flush();

            return $this->redirectToRoute('app_admin_roles_editeur_liste');
        }

        return $this->render('admin/roles/editeur/ajoutFichier.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    #[Route('/edition/{idEditeur}', name: 'edition')]
    public function edition($idEditeur, Request $request, EntityManagerInterface $em, UploaderService $uploaderService, UserRepository $userRepository): Response
    {
        $editeur = $userRepository->find($idEditeur);

        $form = $this->createForm(EditionType::class, $editeur);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mdp = $request->get("edition")["password"];
            $sauvegardeDuMotDePasse = $form->get('password')->getData();
            $image = $form->get("image")->getData();
            $role = $request->get("edition")["roles"][0];

            if ($image == '') {
                $editeur->setPhoto('rien');
                $editeur->setPhotoNom('rien');
            }

            else
            {
                $photoNom = $image->getClientOriginalName();
                $nouveauNomImage = $uploaderService->uploader($image);
                $editeur->setPhoto($nouveauNomImage);
                $editeur->setPhotoNom($photoNom);
            }

            $editeur->setPassword($mdp);
            $editeur->setRoles([$role]);
            $editeur->setRemerciement(false);
            $editeur->setDateCreation(new DateTime());
            $editeur->setSauvegardeDuMotDePasse($sauvegardeDuMotDePasse);


            $em->persist($editeur);
            $em->flush();

            $this->addFlash(
               'success',
               'editeur edité avec success.'
            );

            return $this->redirectToRoute('app_admin_roles_editeur_detail', ['idEditeur'=>$editeur->getId()]);
        }

        return $this->render('admin/roles/editeur/edition.html.twig', [
            "editeur" => $editeur,
            "formEditeur" => $form->createView(),
        ]);
    }

    #[Route('/detail/{idEditeur}', name: 'detail')]
    public function detail($idEditeur, UserRepository $userRepository): Response
    {
        $editeur = $userRepository->find($idEditeur);

        return $this->render('admin/roles/editeur/detail.html.twig', [
            'editeur' => $editeur
        ]);
    }

    #[Route('/suppression/{idEditeur}', name: 'suppression')]
    public function suppresion($idEditeur, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $editeur = $userRepository->find($idEditeur);

        $em->remove($editeur);
        $em->flush();

        $this->addFlash(
           'success',
           'Editeur supprimer avec success.'
        );

        return $this->redirectToRoute('app_admin_roles_editeur_liste');
    }
}
