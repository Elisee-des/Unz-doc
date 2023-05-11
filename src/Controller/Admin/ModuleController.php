<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use App\Form\Admin\Module\AjoutType;
use App\Form\Admin\Module\EditionType;
use App\Repository\AnneeRepository;
use App\Repository\DepartementRepository;
use App\Repository\FiliereRepository;
use App\Repository\LicenceRepository;
use App\Repository\ModuleRepository;
use App\Repository\OptionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/module', name: 'app_admin_module_')]
class ModuleController extends AbstractController
{
    #[Route('/liste/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'liste')]
    public function index($idFiliere, $idOption, $idDepartement, $idAnnee , LicenceRepository $licenceRepository,
     FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
      AnneeRepository $anneeRepository, $idLicence, OptionRepository $optionRepository, ModuleRepository $moduleRepository): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $departement = $departementRepository->find($idDepartement);
        $modules = $moduleRepository->findAllModules($idAnnee);
        $licence = $licenceRepository->find($idLicence);


        return $this->render('admin/module/index.html.twig', [
            "annee" => $annee,
            'licence' => $licence,
            'filiere' => $filiere,
            'departement' => $departement,
            'modules' => $modules["moduleDeCetteAnnee"],
            "option" => $option

        ]);
    }

    #[Route('/ajout/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}', name: 'ajout')]
    public function ajout($idLicence, $idOption, $idAnnee , Request $request, EntityManagerInterface $em, $idFiliere, FiliereRepository $filiereRepository,
     $idDepartement, DepartementRepository $departementRepository, LicenceRepository $licenceRepository, OptionRepository $optionRepository,
     AnneeRepository $anneeRepository ): Response
    {
        $option = $optionRepository->find($idOption);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $licence = $licenceRepository->find($idLicence);
        $annee = $anneeRepository->find($idAnnee);

        $module = new Module();
        $form = $this->createForm(AjoutType::class, $module);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $module->setAnnee($annee);
            $module->setDateCreation(new DateTime());
            $em->persist($module);
            $em->flush();

            $this->addFlash(
               'success',
               'Module ajouté avec success.'
            );

            return $this->redirectToRoute('app_admin_module_liste', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption"=> $option->getId(), "idAnnee" => $annee->getId()]);
        }

        return $this->render('admin/module/ajout.html.twig', [
            "formModule" => $form->createView(),
            "filiere" => $filiere,
            "departement" => $departement,
            "licence" => $licence,
            "option" => $option,
            "annee" => $annee
        ]);
    }

    #[Route('/edition/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'edition')]
    public function edition($idDepartement, $idLicence, $idFiliere, $idOption, $idAnnee, $idModule, AnneeRepository $anneeRepository, Request $request, EntityManagerInterface $em,
     licenceRepository $licenceRepository, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     OptionRepository $optionRepository, ModuleRepository $moduleRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);
        $module = $moduleRepository->find($idModule);

        $form = $this->createForm(EditionType::class, $module);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $module->setAnnee($annee);
            $licence->setDateCreation(new DateTime());
            $em->persist($module);
            $em->flush();

            $this->addFlash(
               'success',
               "Module modifier avec success."
            );

            return $this->redirectToRoute('app_admin_module_detail', ["idDepartement" => $departement->getId(),
             "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId()
            ,"idAnnee" => $annee->getId(), "idModule" => $module->getId()]);
        }

        return $this->render('admin/module/edition.html.twig', [
            "formModule" => $form->createView(),
            "licence" => $licence,
            "filiere" => $filiere,
            "departement" => $departement,
            "annee" => $annee,
            "option" => $option,
            "module" => $module
        ]);
    }

    #[Route('/detail/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'detail')]
    public function detail($idDepartement, $idFiliere, $idLicence, $idOption, $idAnnee, $idModule , AnneeRepository $anneeRepository, licenceRepository $licenceRepository,
     DepartementRepository $departementRepository, FiliereRepository $filiereRepository, OptionRepository $optionRepository,
     ModuleRepository $moduleRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $departement = $departementRepository->find($idDepartement);
        $filiere = $filiereRepository->find($idFiliere);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);
        $module = $moduleRepository->find($idModule);

        return $this->render('admin/module/detail.html.twig', [
            'departement' => $departement,
            'filiere' => $filiere,
            'licence' => $licence,
            'annee' => $annee,
            'option' => $option,
            'module' => $module
        ]);
    }
    
    #[Route('/suppression/{idDepartement}/{idFiliere}/{idLicence}/{idOption}/{idAnnee}/{idModule}', name: 'suppression')]
    public function suppression($idDepartement, $idFiliere, $idLicence, $idOption, $idAnnee, $idModule, licenceRepository $licenceRepository,
     EntityManagerInterface $em, FiliereRepository $filiereRepository, DepartementRepository $departementRepository,
     AnneeRepository $anneeRepository, OptionRepository $optionRepository, ModuleRepository $moduleRepository): Response
    {
        $licence = $licenceRepository->find($idLicence);
        $filiere = $filiereRepository->find($idFiliere);
        $departement = $departementRepository->find($idDepartement);
        $annee = $anneeRepository->find($idAnnee);
        $option = $optionRepository->find($idOption);
        $module = $moduleRepository->find($idModule);


        $em->remove($module);
        $em->flush();

        $this->addFlash(
           'success',
           'Module supprimer avec success'
        );

       return $this->redirectToRoute('app_admin_module_liste', ["idDepartement" => $departement->getId(),
        "idFiliere" => $filiere->getId(), "idLicence" => $licence->getId(), "idOption" => $option->getId(),
         "idAnnee" => $annee->getId(), "idModule" => $module->getId()]);
    }
}
