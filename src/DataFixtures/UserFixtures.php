<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Parcelle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    #Pour que les passwords soient hachés et pas en clair :
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
    }

    // Execute les fixtures via la commande php bin/console doctrine:fixtures:load (charge les fausses données)
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setFirstname('Sullivan');
        $admin->setLastname('Nom');
        $admin->setEmail('admin@demo.com');
        $plainPassword = '1234';
        $passwordHasher = $this->userPasswordHasher->hashPassword($admin, $plainPassword);
        $admin->setPassword($passwordHasher);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setIsActive(true);
        $manager->persist($admin);
        //créer une référence pour l'utiliser dans d'autre fixture (grâce à un get)
        $this->addReference('userAdmin',$admin);

        $user = new User();
        $user->setFirstname('User1');
        $user->setLastname('Nom1');
        $user->setEmail('user1@demo.com');
        $plainPassword = '1234';
        $passwordHasher = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($passwordHasher);
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setIsActive(true);
        $user->setParcelle($this->getReference('parcelle1',Parcelle::class));
        $manager->persist($user);
         $this->addReference('user1',$user);

        $user2 = new User();
        $user2->setFirstname('User2');
        $user2->setLastname('Nom2');
        $user2->setEmail('user2@demo.com');
        $plainPassword = '1234';
        $passwordHasher = $this->userPasswordHasher->hashPassword($user2, $plainPassword);
        $user2->setPassword($passwordHasher);
        $user2->setRoles(['ROLE_USER']);
        $user2->setIsVerified(true);
        $user2->setIsActive(true);
        $user2->setParcelle($this->getReference('parcelle2',Parcelle::class));
        $manager->persist($user2);
        $this->addReference('user2',$user2);

        $manager->flush();
    }
    // permet de dire qu'il faut charger les dépendances suivantes avant de créer les fixtures User
    public function getDependencies(): array
    {
        return [
            ParcelleFixtures::class
        ];
    }
}
