<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute("app_parcelle_index");
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();

                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                #Genere une exception pour tester le catch, le message 'erreur' sera stocké dans la variable $e.
                // throw new Exception("erreur");
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                $this->addFlash("success", "Inscription réussie");
                #Connecte l'utilisateur après l'inscription
                return $security->login($user, 'form_login', 'main');
            } catch (Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de l'inscription");
                #recharge la page et pour afficher le message d'erreur
                return $this->redirectToRoute('app_register');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
