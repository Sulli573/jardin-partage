<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Service\EmailService;
use Symfony\Component\Uid\Uuid;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, EmailService $emailService): Response
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

                // encode le plainPassword (le hash) et on l'insère dans le champs password de $user.
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                // créé un id pour le token, insère le token dans l'entité user, met la vérification de l'utilisateur à faux (car il n'a pas encore cliqué sur le lien)
                $token = Uuid::v4()->toRfc4122();
                $user->setToken($token);
                $user->setIsVerified(false);

                #Genere une exception pour tester le catch, le message 'erreur' sera stocké dans la variable $e.
                // throw new Exception("erreur");
                $entityManager->persist($user);
                $entityManager->flush();


                $confirmationLink = $this->generateUrl(
                    'app_email_confirmation',
                    ['token' => $token],
                    // Le lien url en entier 
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
                $emailService->sendEmail(
                    'sullivan.espeut@gmail.com',
                    $user->getEmail(),
                    'Confirmation Email',
                    'Merci de confirmer votre adresse : <a href="' . $confirmationLink . '">Confirmer mon email</a>'
                );


                $this->addFlash("success", "Un email vous a été envoyé, cliquez sur le lien pour confirmer votre inscription");
                #Connecte l'utilisateur après l'inscription
                // Met l'utilisateur en session
                //Pourquoi je fait ça? un robot peut s'inscrire et se connecter directement : pas de double auhtentification ou confirmation par mail
                //Ne pas faire le return, ne pas connecter diret l'utilisateur qui vient de s'inscrire
                return $this->redirectToRoute('app_login');

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

    // Va exécuter cette logique après le clic de l'utilisateur sur le lien envoyé par courriel
    #[Route('/register/confirmation/{token}', name: 'app_email_confirmation')]
    public function index($token, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // 'token' est le nom du champ dans la base de données
        $user = $userRepository->findOneBy(['token' => $token]);
        if (!$user) {

            throw $this->createNotFoundException('Ce token de confirmation est invalide');
        }
        $user->setToken(null);
        $user->setIsVerified(true);

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('email/index.html.twig', [
            'user' => $user
        ]);
    }
}
