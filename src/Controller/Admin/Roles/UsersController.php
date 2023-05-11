<?php

namespace App\Controller\Admin\Roles;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/admin/roles/tous-les-inscrits/liste', name: 'app_admin_roles_user_liste')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/roles/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/roles/detail/{idUser}', name: 'app_admin_roles_user_detail')]
    public function detail($idUser, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($idUser);

        return $this->render('admin/roles/users/detail.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/admin/roles/banir/{idUser}', name: 'app_admin_roles_user_bani')]
    public function banissement($idUser, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($idUser);

        $user->setRoles(["ROLE_BANI"]);

        $em->persist($user);
        $em->flush();

        $this->addFlash(
           'success',
           "Vous venez de banir avec success ". $user->getNomPrenom() . " avec success. Desormis il le sera impossible de se connecter a son compte UNZ-DOC."
        );

        return $this->redirectToRoute('app_admin_roles_user_detail', ["idUser"=>$user->getId()] );
    }

}
