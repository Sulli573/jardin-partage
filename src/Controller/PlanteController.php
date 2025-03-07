<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/plante')]
final class PlanteController extends AbstractController
{
  
    #[Route('/new', name: 'app_plante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plante = new Plante();
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user=$this->getUser();
            $plante->setParcelle($user->getParcelle());
            $plante->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($plante);
            $entityManager->flush();

            $this->addFlash("success","La plante a bien été ajoutée");

            return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plante/new.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plante_show', methods: ['GET'])]
    public function show(Plante $plante): Response
    {
        return $this->render('plante/show.html.twig', [
            'plante' => $plante,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanteType::class, $plante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plante/edit.html.twig', [
            'plante' => $plante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plante_delete', methods: ['POST'])]
    public function delete(Request $request, Plante $plante, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plante->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($plante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
    }
}
