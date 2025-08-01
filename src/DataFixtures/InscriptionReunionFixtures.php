<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Reunion;
use App\Entity\InscriptionReunion;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InscriptionReunionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $inscriptionReunion = new InscriptionReunion();
        $inscriptionReunion->setReunion($this->getReference('reunion', Reunion::class));
        $inscriptionReunion->setUser($this->getReference('user1', User::class));
        $inscriptionReunion->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($inscriptionReunion);

        $inscriptionReunion = new InscriptionReunion();
        $inscriptionReunion->setReunion($this->getReference('reunion', Reunion::class));
        $inscriptionReunion->setUser($this->getReference('user2', User::class));
        $inscriptionReunion->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($inscriptionReunion);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ReunionFixtures::class,
        ];
    }
}
