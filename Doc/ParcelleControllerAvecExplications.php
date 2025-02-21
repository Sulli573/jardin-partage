<?php

namespace App\Controller;

use App\Entity\Parcelle;
use App\Form\ParcelleType;
use App\Repository\ParcelleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Possède toutes les routes qui contient la logique des parcelles (ajout, suppression, etc)
 */
final class ParcelleController extends AbstractController
{
    /**
     * Afficher toutes les infos de toutes les parcelles
     */
    #[Route('/parcelles', name: 'parcelles')] // name:'parcelles' est une clé qu'on va pouvoir mettre dans les ancres pour aller sur cette URL (comme dans SharepOint online quand je mettais l'url dans l'image etc...)
    public function index(ParcelleRepository $parcelleRepository): Response
    {
        $parcelles = $parcelleRepository->findAll();

        #On envoie toutes les parcelles à la vue grâce à la clé 'parcelles'
        return $this->render('parcelle/index.html.twig', [
            'parcelles' => $parcelles,
        ]);
    }

    /**
     * Ajouter une parcelle via une formulaire
     * 
     * Request correpond à la requete/demande de la apge au serveur
     * Donc request contient toutes les donnée de ma demande
     * $_GET/$_POST/$_SERVER/$_COOKIE, etc.
     */
    #[Route('/parcelle/add', name: 'parcelle_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un objet vide parcelle
        $parcelle = new Parcelle;
        // Créer le formulaire avec la configuration
        $form = $this->createForm(ParcelleType::class, $parcelle);
        // Vérifier les données et les transformer de tableau à objet Parcelle (mapping)
        // On vérifie les données pour vérifier les typages et les contraintes (pas être null, supérficie inf à 100m², etc.)
        // Transformer de tableau à objet, pour mieux manipuler les données.
        $form->handleRequest($request); 

        // Traiter les données (Ajouter la parcelle en base de donnée)
        if ($form->isSubmitted() && $form->isValid()) {
            $parcelle->setCreatedAt(new \DateTimeImmutable());

            // On va demander au gestionnaire des entités d'ajouter la parcelle en base de donnée
            // On persiste, on met dans une boite (similaire a commit avec git)
            $entityManager->persist($parcelle);
            // et on flush (push avec git), ajoute réllement en base de donnée
            $entityManager->flush();

            // réactualiser la page après le traitement des données pour éviter que l'utilisateur spam le bouton ajout du formulaire
            return $this->redirectToRoute('parcelle_add'); // Similaire à un a href="..."
        }
        #chemin ou est la vue (parcelle/add.html.twig) alors que 'parcelle_add' est la route, l'url
        return $this->render('parcelle/add.html.twig', [
            'formAdd' => $form, // On donne à la vue, le formulaire à générer
        ]);
    }
}

