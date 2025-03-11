<?php

namespace App\Controller\Admin;

use App\Entity\Plante;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/plante')]
final class AdminPlanteController extends AbstractController
{
    #[Route(name: 'app_admin_plante_index', methods: ['GET'])]
    public function index(PlanteRepository $planteRepository): Response
    {
        return $this->render('admin_plante/index.html.twig', [
            'plantes' => $planteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_plante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plante = new Plante();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $entityManager->persist($plante);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_plante_index', [], Response::HTTP_SEE_OTHER);
            }catch(Exception $e) {
                $this->addFlash("error","Une erreur est survenue lors de l'ajout de la plante");
                return $this->redirectToRoute('app_admin_plante_new');
            }
        }

        return $this->render('admin_plante/new.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plante_show', methods: ['GET'])]
    public function show(Plante $plante): Response
    {
        return $this->render('admin_plante/show.html.twig', [
            'plante' => $plante,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_plante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_plante_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_plante/edit.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plante_delete', methods: ['POST'])]
    public function delete(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plante->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($plante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_plante_index', [], Response::HTTP_SEE_OTHER);
    }
}
