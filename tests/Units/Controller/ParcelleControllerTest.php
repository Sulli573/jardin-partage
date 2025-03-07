<?php

namespace App\Tests\Units\Controller;

use App\Entity\User;
use App\Entity\Parcelle;
use App\Controller\ParcelleController;
use App\Repository\ParcelleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ParcelleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $parcelleRepository = $this->createMock(ParcelleRepository::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $parcelle = new Parcelle(); // Créez un objet Parcelle pour le test
        $user->setParcelle($parcelle);

        $tokenStorage->method('getToken')->willReturn($token);
        $token->method('getUser')->willReturn($user);
        $user->method('getParcelle')->willReturn($parcelle);

        $controller = new ParcelleController();
        $controller->setContainer($this->getContainer());

        
        $response = $controller->index($parcelleRepository);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('parcelle/index.html.twig', $response->headers->get('X-Template'));
        $this->assertSame('parcelle_test', $response->headers->get('X-Template-Vars')['parcelle']);
    }
}