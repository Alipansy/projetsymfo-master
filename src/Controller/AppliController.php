<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppliController extends AbstractController
{   #[Route('/', name: 'home')]
    public function home(): Response 
    {
        return $this->render('base.html.twig'); 
    }
    #[Route('/appli', name: 'app_appli')]
    public function index(): Response
    {
        return $this->render('appli/index.html.twig', [
            'controller_name' => 'AppliController',
        ]);
    }
    
}                                                                                               
