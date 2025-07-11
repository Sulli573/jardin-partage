<?php

namespace App\Controller;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\Reunion;
use App\Form\ReunionType;
use App\Entity\InscriptionReunion;
use App\Repository\InscriptionReunionRepository;
use App\Repository\ReunionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reunion')]
final class ReunionController extends AbstractController
{
    #[Route(name: 'app_reunion_index', methods: ['GET'])]
    public function index(ReunionRepository $reunionRepository): Response
    {
        return $this->render('reunion/index.html.twig', [
            'reunions' => $reunionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reunion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reunion = new Reunion();
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reunion);
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/new.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_show', methods: ['GET'])]
    public function show(Reunion $reunion): Response
    {
        return $this->render('reunion/show.html.twig', [
            'reunion' => $reunion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reunion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/edit.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_delete', methods: ['POST'])]
    public function delete(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reunion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reunion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/register/{id}', name: 'app_reunion_register', methods: ['GET'])]
    public function register(Request $request, Reunion $reunion, EntityManagerInterface $entityManager, InscriptionReunionRepository $inscriptionReunionRepository): Response
    {
        //ajoute l'utilisateur connecté à la reunion
        //recupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est déjà inscrit à la réunion
        $existingInscription = $inscriptionReunionRepository->findOneBy([
            "user" => $user,
            "reunion" => $reunion
        ]);

        if ($existingInscription) {
            $this->addFlash("danger", "Vous êtes déjà inscrit à cette réunion");
            return $this->redirectToRoute('app_home');
        }

        $inscription = new InscriptionReunion();
        $inscription->setReunion($reunion);
        $inscription->setUser($user);
        $inscription->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));
        $entityManager->persist($inscription);
        $entityManager->flush();

        $this->addFlash("success", "Inscription à la réunion à " . $reunion->getLieu() . " du " . $reunion->getDate()->format("d/m/Y H:i") . " validée");

        return $this->redirectToRoute('app_home');
    }

    #[Route('/unsubscribe/{id}', name: 'app_reunion_unsubscribe', methods: ['GET'])]
    public function unsubscribe(Request $request, Reunion $reunion, EntityManagerInterface $entityManager, InscriptionReunionRepository $inscriptionReunionRepository): Response
    {
        //Désinscire l'utilisateur de la réunion
        $user = $this->getUser();
        // récupéréer l'inscription d l'utilisateur à la réunion:
        $inscription = $inscriptionReunionRepository->findOneBy([
            "user" => $user,
            "reunion" => $reunion
        ]);

        if (!$inscription) {
            $this->addFlash("danger", "Vous n'étes pas inscrit à cette réunion");
            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($inscription);
        $entityManager->flush();

        $this->addFlash("success", "Désinscription de la réunion à " . $reunion->getLieu() . " du " . $reunion->getDate()->format("d/m/Y H:i") . " réussie");

        return $this->redirectToRoute('app_home');
    }
}
