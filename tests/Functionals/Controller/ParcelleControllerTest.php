<?php

namespace App\Tests\Functionals\Controller;

use App\Entity\User;
use App\Entity\Parcelle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Tests fonctionnels pour le ParcelleController
 * 
 * Ces tests vérifient le comportement du contrôleur de parcelles dans différents scénarios :
 * - Accès à la page des parcelles sans authentification
 * - Accès à la page des parcelles avec un utilisateur authentifié
 * 
 * Les tests fonctionnels simulent des requêtes HTTP complètes et vérifient
 * les réponses, y compris le rendu HTML et les redirections.
 */
class ParcelleControllerTest extends WebTestCase
{
    /**
     * Client HTTP utilisé pour simuler les requêtes
     */
    private $client;
    
    /**
     * EntityManager utilisé pour interagir avec la base de données de test
     */
    private $entityManager;

    /**
     * Configuration initiale avant chaque test
     * 
     * Initialise le client HTTP et l'EntityManager pour interagir avec la base de données.
     * Cette méthode est exécutée avant chaque test.
     */
    protected function setUp(): void
    {
        // Création d'un client HTTP pour simuler les requêtes
        $this->client = static::createClient();
        
        // Récupération de l'EntityManager pour interagir avec la base de données
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Teste l'accès à la page des parcelles sans authentification
     * 
     * Vérifie que :
     * - Un utilisateur non authentifié est redirigé vers la page de connexion
     *   lorsqu'il tente d'accéder à la page des parcelles
     */
    public function testIndexRedirectWhenNotAuthenticated(): void
    {
        // Envoi d'une requête GET à la route /parcelle sans être authentifié
        $this->client->request('GET', '/parcelle');
        
        // Vérification que l'utilisateur est redirigé vers la page de connexion
        $this->assertResponseRedirects('/login');
    }

    /**
     * Teste l'accès à la page des parcelles avec un utilisateur authentifié
     * 
     * Vérifie que :
     * - Un utilisateur authentifié peut accéder à la page des parcelles
     * - La page affiche correctement les informations de sa parcelle
     * - Les éléments HTML attendus sont présents dans la réponse
     */
    public function testIndexWithAuthenticatedUser(): void
    {
        // 1. Préparation des données de test
        
        // Création d'un utilisateur de test
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstname('Test');
        $user->setLastname('User');
        
        // Hashage du mot de passe avec le service approprié
        $user->setPassword(
            $this->client->getContainer()->get('security.user_password_hasher')->hashPassword(
                $user,
                'password123'
            )
        );

        // Création d'une parcelle associée à l'utilisateur
        $parcelle = new Parcelle();
        $parcelle->setNumber(1);
        $parcelle->setSize(50);
        $parcelle->setCreatedAt(new \DateTimeImmutable());
        
        // Établissement de la relation bidirectionnelle entre User et Parcelle
        $user->setParcelle($parcelle);
        $parcelle->setOwner($user);

        // Persistance des entités dans la base de données de test
        $this->entityManager->persist($parcelle);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // 2. Authentification de l'utilisateur
        
        // Connexion de l'utilisateur (simule une session authentifiée)
        $this->client->loginUser($user);

        // 3. Exécution de la requête
        
        // Envoi d'une requête GET à la route /parcelle en étant authentifié
        $crawler = $this->client->request('GET', '/parcelle');

        // 4. Vérification des résultats
        
        // Vérification que la réponse est un succès (code 200)
        $this->assertResponseIsSuccessful();
        
        // Vérification que la carte de la parcelle est présente dans le HTML
        $this->assertSelectorExists('.card');
        
        // Vérification que le titre "Ma parcelle" est présent
        $this->assertSelectorTextContains('h2', 'Ma parcelle');
        
        // Vérification que le numéro de la parcelle (#1) est affiché
        $this->assertSelectorTextContains('h3 p', 'Parcelle n°1');
        
        // Vérification que la taille de la parcelle (50 m²) est affichée
        $this->assertSelectorTextContains('.card', '50 m²');
    }

    /**
     * Nettoyage après chaque test
     * 
     * Ferme la connexion à la base de données pour éviter les fuites de mémoire.
     * Cette méthode est exécutée après chaque test.
     */
    protected function tearDown(): void
    {
        // Appel de la méthode parente pour le nettoyage standard
        parent::tearDown();
        
        // Fermeture de la connexion à la base de données
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
} 