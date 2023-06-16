<?php

namespace App\Controller;
use App\Entity\Membre;
use App\Entity\Vehicule;
use App\Entity\Commande;
use App\Form\FormCommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/{vehiculeId}', name: 'app_commande')]
    public function commande(Request $request, EntityManagerInterface $entityManager, $vehiculeId, Commande $commande): Response
    {
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($vehiculeId);
        if (!$vehicule) {
            throw $this->createNotFoundException("Le véhicule n'existe pas.");
        }
        $commande = new Commande();

        $commande->setidVehicule($vehicule);

        $form = $this->createForm(FormCommandeType::class, $commande);
        $membre = $this->getUser();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setIdMembre($this->getUser());
            $dateDebut = $commande->getDateHeureDepart();
            $dateFin = $commande->getDateHeureFin();
            $nombreJours = $dateFin->diff($dateDebut)->days;
            $prixJournalier = $commande->getIdVehicule()->getPrixJournalier();
            $prixTotal = $prixJournalier * $nombreJours;

            $commande
            ->setDateEnregistrement(new \DateTime)
            ->setPrixTotal($prixTotal)
            ->setidMembre($membre);

            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('commande/formCommande.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
