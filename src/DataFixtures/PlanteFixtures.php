<?php

namespace App\DataFixtures;

use App\Entity\Plante;
use App\Entity\Parcelle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PlanteFixtures extends Fixture implements DependentFixtureInterface
{
    #Pour que les passwords soient hachés et pas en clair :
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
    }
    //FAIRE AVEC PARCELLE , faire une boucle for, FAIRE PAREIL POUR PLANTES
    // Execute les fixture via la commande php bin/console doctrine:fixtures:load (charge les fausses données)
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i < 10; $i++) { 
            $plante = new Plante();
            $plante->setNom('salade');
            $plante->setType('legume');
            $plante->setCreatedAt(new \DateTimeImmutable());
            $plante->setDatePlantation(new \DateTimeImmutable());
            $plante->setPeriodeCroissance($i);
            //une plante par parcelle, la plante1 dans la parcelle1, la plante2 dans la parcelle2 etc...
            $plante->setParcelle($this->getReference('parcelle'.$i, Parcelle::class));
            $manager->persist($plante); // mettre le persiste dans la boucle for
            
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ParcelleFixtures::class
        ];
    }
}
