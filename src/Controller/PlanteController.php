<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/plante')]
final class PlanteController extends AbstractController
{
    #[Route(name: 'app_plante_index', methods: ['GET'])]
    public function index(PlanteRepository $planteRepository): Response
    {
        return $this->render('plante/index.html.twig', [
            'plantes' => $planteRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_plante_show', methods: ['GET'])]
    public function show(Plante $plante): Response
    {
        return $this->render('plante/show.html.twig', [
            'plante' => $plante,
        ]);
    }
}
