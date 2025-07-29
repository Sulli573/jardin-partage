<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/user')]
final class AdminUserController extends AbstractController
{
    #[Route(name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            "roles" => $this->getParameter("security.role_hierarchy.roles")
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //Si activer, pas de date, metrte à la date null
                //Si désactiver, la date
                //car au bout d'un an de désactivation => suppression
                if($user->isActive()) {
                    $user->setDesactivateAt(null);
                }
                else {
                    // l'antislash devant DateTime ou DateTimeImmutable petmet de ne pas mettre un use
                    $user->setDesactivateAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
                # \Exception équivaut à mettre en haut du fichier use Exception;
            } catch (\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenur lors de la modification de l'utilisateur");
                return $this->redirectToRoute('app_admin_user_edit');
            }
        }

        return $this->render('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    // route non utilisée car finalement mis la logique dans formulaire avec checkbox 
    #[Route('/{id}/activation', name: 'app_admin_user_activation', methods: ['GET'])]
    public function activation(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $active = $user->isActive();
        $user->setIsActive(!$active);
        $this->addFlash('success','L\'utilisateur est maintenant ' . $active ? 'désactivé' : 'activé');

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
