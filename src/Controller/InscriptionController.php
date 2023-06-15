<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Membre;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\membrePasswordHasherInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    // public function index(): Response
    // {
    //     return $this->render('inscription/index.html.twig', [
    //         'controller_name' => 'InscriptionController',
    //     ]);
    // }
    public function formInscription(Request $request, UserPasswordHasherInterface $membrePasswordHasher, EntityManagerInterface $entityManager) : Response
    {
        $membre = new Membre();
        $form = $this->createForm(InscriptionType::class, $membre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $membre->setPassword(
                $membrePasswordHasher->hashPassword(
                    $membre,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render('inscription/index.html.twig', [
            'inscription' => $form
        ]);

    
}
}
