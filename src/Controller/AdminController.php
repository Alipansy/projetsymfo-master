<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Vehicule;
use App\Form\ArticleType;
use App\Form\FormVehiculeType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
}
