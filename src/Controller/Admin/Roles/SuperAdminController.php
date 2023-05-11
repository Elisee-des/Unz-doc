<?php

namespace App\Controller\Admin\Roles;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/roles/super/admin', name: 'app_admin_roles_super_admin_')]
class SuperAdminController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_SUPER_ADMIN", $role)) {
                $superaAdmins[] = $user;
            }
        }

        return $this->render('admin/roles/super_admin/index.html.twig', [
            'superAdmins' => $superaAdmins
        ]);
    }

    #[Route('/detail/{idSuperAdmin}', name: 'detail')]
    public function detail($idSuperAdmin, UserRepository $userRepository): Response
    {
        $superAdmin = $userRepository->find($idSuperAdmin);

        return $this->render('admin/roles/super_admin/detail.html.twig', [
            'superAdmin' => $superAdmin
        ]);
    }

    #[Route('/detail-du-developpeur', name: 'detail_developpeur')]
    public function detailDeveloppeur(): Response
    {

        return $this->render('admin/roles/super_admin/detailDeveloppeur.html.twig');
    }
}
