<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Vehicule;
use App\Form\FormVehiculeType;
use Doctrine\ORM\EntityManager;
use App\Form\FormEditMembreType;
use App\Repository\MembreRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormTypeInterface;



class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    #[Route('/admin/vehicule' , name: 'admin_vehicule')]
    public function adminVehicule(VehiculeRepository $repo, EntityManagerInterface $manager)
    {
    $colonnes = $manager->getClassMetadata(Vehicule::class)->getFieldNames();
    // dd($colonnes);
    $vehicule = $repo->findAll();
    return $this->render('admin/gestion_vehicule.html.twig', [
        "colonnes" => $colonnes, 
        "vehicule" => $vehicule
    ]);
    }
    #[Route('/admin/vehicule/edit/{id}' , name: "admin_vehicule_edit")]

    #[Route('/admin/vehicule/new' , name: "admin_vehicule_new")]
    public function formVehicule(Request $request, EntityManagerInterface $manager, Vehicule $vehicule = null) : Response 
    {
        if($vehicule == null)
        {
            $vehicule = new Vehicule;
        }
        $form = $this->createForm(FormVehiculeType::class, $vehicule); 
        $form->handleRequest($request); 
        if($form->isSubmitted() &&$form->isValid())
        {
            $vehicule->setDateEnregistrement(new \DateTime); 
            $manager->persist($vehicule); 
            $manager->flush();
            $this->addFlash('success',"Le véhicule à bien été enregistré"); 
            return $this->redirectToRoute('admin_vehicule'); 
        }



        return $this->render('admin/formVehicule.html.twig', 
    [
        'form' => $form, 
        'editMode' => $vehicule->getId()!=null
    ]);
    }
    
    #[Route('/admin/vehicule/delete/{id}' , name: 'admin_vehicule_delete')]
    public function deleteVehicule(Vehicule $vehicule, EntityManagerInterface $manager)
    {
        $manager->remove($vehicule); 
        $manager->flush(); 
        $this->addFlash('success', "l'article à bel et bien été supprimé");
        return $this->redirectToRoute('admin_vehicule'); 

    }
    #[Route('/admin/membre' , name: 'admin_membre')]
    public function adminMembre(MembreRepository $repo, EntityManagerInterface $manager)
    {
    $colonnes = $manager->getClassMetadata(Membre::class)->getFieldNames();
    // dd($colonnes);
    $membre = $repo->findAll();
    return $this->render('admin/gestionMembre.html.twig', [
        "colonnes" => $colonnes, 
        "membre" => $membre
    ]);
    }   
    #[Route('/admin/membre/edit/{id}' , name: "admin_membre_edit")]
    public function editMembre(Request $request, EntityManagerInterface $manager, Membre $membre = null) : Response
    {
        if($membre == null)
        {
            // $membre = new Membre;
            return $this->redirectToRoute('gestion_membre'); 
        }

        $form = $this->createForm(FormEditMembreType::class, $membre); 
        $form->handleRequest($request); 
        if($form->isSubmitted() &&$form->isValid())
        {
            $membre->setDateEnregistrement(new \DateTime); 
            $manager->persist($membre); 
            $manager->flush();
            $this->addFlash('success',"Le rôle de l'utilisateur à bien été modifié"); 
            return $this->redirectToRoute('admin_membre'); 
        }



        return $this->render('admin/formMembre.html.twig', 
    [
        'form' => $form, 
        'editMode' => $membre->getId()!=null
    ]);
    }
    #[Route('/admin/membre/delete/{id}' , name: 'admin_membre_delete')]
    public function deleteMembre(Membre $membre, EntityManagerInterface $manager)
    {
        $manager->remove($membre); 
        $manager->flush(); 
        $this->addFlash('success', "l'utilisateur à bel et bien été supprimé");
        return $this->redirectToRoute('admin_membre'); 

    }


}

