<?php

namespace App\Controller\Main;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/main/message', name: 'app_main_message')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $mesasge = new Message();

        if (isset($_POST["contact_message"])) {
            $nomPrenom = $request->get("nomPrenom");
            $numero = $request->get("numero");
            $sujet = $request->get("sujet");
            $msg = $request->get("message");
            
            $mesasge
                ->setNomPrenom($nomPrenom)
                ->setnumero($numero)
                ->setSujet($sujet)
                ->setMessage($msg)
                ;
            
            $em->persist($mesasge);
            $em->flush();

            return $this->redirectToRoute('app_main_message_information');

        }

        return $this->render('main/page_principal/index.html.twig');
    }

    #[Route('/main/message/information', name: 'app_main_message_information')]
    public function contact(): Response
    {
        return $this->render('main/message/index.html.twig');
    }
}
