<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Entity\Parcelle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParcelleControllerTest extends WebTestCase
{
    private static $client;
    private static $entityManager;
    private static $passwordHasher;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::$entityManager = self::$client->getContainer()->get('doctrine')->getManager();
        self::$passwordHasher = self::$client->getContainer()->get('security.user_password_hasher');
    }

    protected function setUp(): void
    {
        // Nettoyer la base de données dans le bon ordre pour respecter les contraintes de clé étrangère
        self::$entityManager->createQuery('DELETE FROM App\Entity\User u')->execute();
        self::$entityManager->createQuery('DELETE FROM App\Entity\Parcelle p')->execute();
    }

    public function testIndexWhenUserHasParcelle(): void
    {
        // Créer un utilisateur
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(self::$passwordHasher->hashPassword($user, 'password'));
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setRoles(['ROLE_USER']);

        // Créer une parcelle
        $parcelle = new Parcelle();
        $parcelle->setNumber(1);
        $parcelle->setSize(100);
        $parcelle->setCreatedAt(new \DateTimeImmutable());
        $parcelle->setOwner($user);

        // Sauvegarder en base de données
        self::$entityManager->persist($user);
        self::$entityManager->persist($parcelle);
        self::$entityManager->flush();

        // Connecter l'utilisateur
        self::$client->loginUser($user);

        // Faire la requête
        self::$client->request('GET', '/parcelle');

        // Vérifier la réponse
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Ma parcelle');
        $this->assertSelectorTextContains('.card', 'Propriétaire: John Doe');
        $this->assertSelectorTextContains('.card', 'Superficie: 100 m²');
    }

    public function testIndexWhenUserHasNoParcelle(): void
    {
        // Créer un utilisateur sans parcelle
        $user = new User();
        $user->setEmail('test2@example.com');
        $user->setPassword(self::$passwordHasher->hashPassword($user, 'password'));
        $user->setFirstname('Jane');
        $user->setLastname('Doe');
        $user->setRoles(['ROLE_USER']);

        // Sauvegarder en base de données
        self::$entityManager->persist($user);
        self::$entityManager->flush();

        // Connecter l'utilisateur
        self::$client->loginUser($user);

        // Faire la requête
        self::$client->request('GET', '/parcelle');

        // Vérifier la réponse
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Ma parcelle');
        $this->assertSelectorNotExists('.card');
    }

    protected function tearDown(): void
    {
        // Nettoyer la base de données dans le bon ordre
        self::$entityManager->createQuery('DELETE FROM App\Entity\User u')->execute();
        self::$entityManager->createQuery('DELETE FROM App\Entity\Parcelle p')->execute();
    }

    public static function tearDownAfterClass(): void
    {
        self::$entityManager->close();
        self::$entityManager = null;
        self::$client = null;
    }
} 