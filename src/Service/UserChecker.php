<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException("Votre compte n'est pas vérifié.");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        $user->setLastConnection(new \DateTime('now', new \DateTimeZone("Europe/Paris")));
        $this->entityManager->flush();
    }
}
?>