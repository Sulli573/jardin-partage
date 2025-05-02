<?php

namespace App\Tests\Units\Entity;

use App\Entity\Parcelle;
use App\Entity\Plante;
use App\Entity\User;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * Classe de test pour l'entité Parcelle
 * 
 * Cette classe teste toutes les méthodes et propriétés de l'entité Parcelle
 * pour s'assurer qu'elles fonctionnent correctement.
 */
class ParcelleTest extends TestCase
{
    /**
     * Instance de l'entité Parcelle utilisée pour les tests
     */
    private Parcelle $parcelle;

    /**
     * Méthode exécutée avant chaque test
     * 
     * Initialise une nouvelle instance de Parcelle pour chaque test
     * afin d'éviter les effets de bord entre les tests.
     */
    protected function setUp(): void
    {
        $this->parcelle = new Parcelle();
    }

    /**
     * Teste les méthodes setNumber et getNumber
     * 
     * Vérifie que le numéro de parcelle est correctement enregistré et récupéré.
     */
    public function testNumber(): void
    {
        $number = 5;
        $this->parcelle->setNumber($number);
        $this->assertEquals($number, $this->parcelle->getNumber());
    }

    /**
     * Teste les méthodes setSize et getSize
     * 
     * Vérifie que la taille de la parcelle est correctement enregistrée et récupérée.
     * La taille est exprimée en mètres carrés.
     */
    public function testSize(): void
    {
        $size = 75.5;
        $this->parcelle->setSize($size);
        $this->assertEquals($size, $this->parcelle->getSize());
    }

    /**
     * Teste les méthodes setCreatedAt et getCreatedAt
     * 
     * Vérifie que la date de création est correctement enregistrée et récupérée.
     * Utilise assertSame pour vérifier que c'est exactement le même objet DateTimeImmutable.
     */
    public function testCreatedAt(): void
    {
        $date = (new \DateTimeImmutable())->setTimezone(new DateTimeZone('Europe/Paris'));
        $this->parcelle->setCreatedAt($date);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $this->parcelle->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    /**
     * Teste la relation bidirectionnelle entre Parcelle et User
     * 
     * Vérifie que :
     * - setOwner associe correctement un utilisateur à la parcelle
     * - getOwner retourne l'utilisateur associé
     * - La relation inverse est également mise à jour (User.parcelle)
     */
    public function testOwner(): void
    {
        // Création d'un utilisateur de test
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        
        // Association de l'utilisateur à la parcelle
        $this->parcelle->setOwner($user);
        
        // Vérification que la parcelle est bien associée à l'utilisateur
        $this->assertSame($user, $this->parcelle->getOwner());
        
        // Vérification que l'utilisateur est bien associé à la parcelle (relation inverse)
        $this->assertSame($this->parcelle, $user->getParcelle());
    }

    /**
     * Teste les méthodes de gestion de la collection de plantes
     * 
     * Vérifie que :
     * - getPlantes retourne une collection vide au départ
     * - addPlante ajoute correctement une plante à la collection
     * - La relation inverse est également mise à jour (Plante.parcelle)
     * - removePlante supprime correctement une plante de la collection
     */
    public function testPlantes(): void
    {
        // Vérifier que la collection est vide au départ
        $this->assertCount(0, $this->parcelle->getPlantes());
        
        // Création d'une plante de test
        $plante = new Plante();
        $plante->setNom('Tomate');
        $plante->setType('Légume');
        
        // Ajout de la plante à la parcelle
        $this->parcelle->addPlante($plante);
        
        // Vérification que la plante a été ajoutée à la collection
        $this->assertCount(1, $this->parcelle->getPlantes());
        $this->assertTrue($this->parcelle->getPlantes()->contains($plante));
        
        // Vérification que la relation inverse a été mise à jour
        $this->assertSame($this->parcelle, $plante->getParcelle());
        
        // Suppression de la plante de la parcelle
        $this->parcelle->removePlante($plante);
        
        // Vérification que la plante a été supprimée de la collection
        $this->assertCount(0, $this->parcelle->getPlantes());
        $this->assertFalse($this->parcelle->getPlantes()->contains($plante));
    }

    /**
     * Teste la méthode __toString
     * 
     * Vérifie que la méthode __toString retourne une chaîne de caractères
     * au format "numéro #X" où X est le numéro de la parcelle.
     */
    public function testToString(): void
    {
        $number = 3;
        $this->parcelle->setNumber($number);
        $this->assertEquals("numéro #$number", $this->parcelle->__toString());
    }
} 