<?php

namespace App\Controller\Admin;

use App\Entity\Parcelle;
use App\Form\ParcelleType;
use App\Repository\ParcelleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/parcelle')]
final class AdminParcelleController extends AbstractController
{
    #[Route(name: 'app_admin_parcelle_index', methods: ['GET'])]
    public function index(ParcelleRepository $parcelleRepository): Response
    {
        return $this->render('admin_parcelle/index.html.twig', [
            'parcelles' => $parcelleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_parcelle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $parcelle = new Parcelle();
        $form = $this->createForm(ParcelleType::class, $parcelle);
        $form->handleRequest($request);
        #Soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $parcelle->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($parcelle);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_parcelle/new.html.twig', [
            'parcelle' => $parcelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_parcelle_show', methods: ['GET'])]
    public function show(Parcelle $parcelle): Response
    {
        return $this->render('admin_parcelle/show.html.twig', [
            'parcelle' => $parcelle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_parcelle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parcelle $parcelle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParcelleType::class, $parcelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_parcelle/edit.html.twig', [
            'parcelle' => $parcelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_parcelle_delete', methods: ['POST'])]
    public function delete(Request $request, Parcelle $parcelle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parcelle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($parcelle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_parcelle_index', [], Response::HTTP_SEE_OTHER);
    }
}
