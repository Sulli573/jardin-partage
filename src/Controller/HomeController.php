<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Entity\Parcelle;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')] // Sur les liens du site il faura mettre la clé (app_home) et pas l'url pour aller sur cette page
    public function index(EntityManagerInterface $entityManager): Response
    {
        // $parcelle = new Parcelle();
        // $parcelle->setNumber(1);
        // $parcelle->setSize(20);
        // $parcelle->setOwner('Sullivan');
        // $parcelle->setCreatedAt(new \DateTimeImmutable());

        // $entityManager->persist($parcelle);

        // $plante = new Plante();
        // $plante->setNom('salade');
        // $plante->setType('légume');
        // $plante->setPeriodeCroissance(52);
        // $plante->setDatePlantation(new \DateTime());
        // $plante->setParcelle($parcelle);
        // $plante->setCreatedAt(new \DateTimeImmutable());

        // $entityManager->persist($plante);
        // $entityManager->flush();

        return $this->render('home/index.html.twig');
    }
}
