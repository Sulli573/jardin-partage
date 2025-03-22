<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



final class ProfilController extends AbstractController{

    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    #[Route('/profil/new_password', name: 'app_profil_new_password')]
    public function new_password(Request $request, UserPasswordHasherInterface $passwordHasher) : Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            /** @var User $user */
            $user=$this->getUser();
            // Encode(hash) the plain password, and set it.
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $this->entityManager->flush();

            $this->addFlash("success","Votre mot de passe a bien été modifié");
            return $this->redirectToRoute('app_profil');
            
        }

        return $this->render('profil/new_password.html.twig',[
            'form'=>$form,
        ]);

    }
}
