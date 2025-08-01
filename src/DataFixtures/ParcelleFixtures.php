<?php

namespace App\DataFixtures;

use App\Entity\Parcelle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParcelleFixtures extends Fixture
{
    //FAIRE AVEC PARCELLE , faire une boucle for, FAIRE PAREIL POUR PLANTES
    // Execute les fixture via la commande php bin/console doctrine:fixtures:load (charge les fausses données)
    public function load(ObjectManager $manager): void
    {   
        for ($i=1; $i < 10; $i++) { 
            $parcelle = new Parcelle();
            $parcelle->setNumber($i);
            $parcelle->setSize($i*2);
            $parcelle->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($parcelle); //met chaque parcelle dans la "boçite) nue par une jusqu'à 10
            //la référence (va créer parcelle1, parcelle2 etc...) va pouvoir etre utilisée dans une autre fixture
            $this->addReference('parcelle'.$i,$parcelle);
        }
        
        $manager->flush(); // met toutes les parcelles en base de données.  
    }
    
}
