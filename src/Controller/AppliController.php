<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppliController extends AbstractController
{   #[Route('/', name: 'home')]
    public function home(VehiculeRepository $vehiculeRepository): Response 
    {
        $vehicule = $vehiculeRepository->findAll();
        return $this->render('base.html.twig',[
            'vehicule' => $vehicule,
        ]); 
    }
    #[Route('/appli', name: 'app_appli')]
    public function index(): Response
    {
        return $this->render('appli/index.html.twig', [
            'controller_name' => 'AppliController',
        ]);
    }
    
}                                                                                               
