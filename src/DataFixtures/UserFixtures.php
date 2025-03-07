<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    #Pour que les passwords soient hachés et pas en clair :
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
    }

    // Execute les fixture via la commande php bin/console doctrine:fixtures:load (charge les fausses données)
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Sullivan');
        $user->setLastname('Nom');
        $user->setEmail('test@demo.com');

        $plainPassword = '1234';
        $passwordHasher = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($passwordHasher);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}
