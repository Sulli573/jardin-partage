<?php

namespace App\DataFixtures;

use App\Entity\Reunion;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ReunionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $reunion= new Reunion();
        $reunion->setDate(new \DateTime('now'));
        $reunion->setLieu("Toulouse");
        $reunion->setSubject("Ceci est la prochaine réunion à " . $reunion->getLieu() . " le " .$reunion->getDate()->format("d/m/Y H:i"));
        $reunion->setContent("Nous parlerons de ...");
        $manager->persist($reunion);
        $this->addReference('reunion',$reunion);

        $manager->flush();
    }
}
