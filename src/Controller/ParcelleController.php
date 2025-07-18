<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Parcelle;
use App\Form\ParcelleType;
use App\Repository\ParcelleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//Pour affchierla page des parcelles

// Prefix de chaque route

#[IsGranted('ROLE_USER')]
final class ParcelleController extends AbstractController
{
    #[Route('/parcelle', name: 'app_parcelle_index', methods: ['GET'])]
    public function index(ParcelleRepository $parcelleRepository): Response
    {
        #recupérer l'utilisateur connecté
        /** @var User $user */
        $user = $this->getUser();
        #recupérer sa parcelle
        $parcelle = $user->getParcelle();
        return $this->render('parcelle/index.html.twig', [
            'parcelle' => $parcelle
        ]);
    }
}
