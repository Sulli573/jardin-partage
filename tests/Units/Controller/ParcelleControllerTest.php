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
        /** @var ParcelleRepository&\PHPUnit\Framework\MockObject\MockObject $parcelleRepository */
        $parcelleRepository = $this->createMock(ParcelleRepository::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $user */
        $user = $this->createMock(User::class);
        $token = $this->createMock(TokenInterface::class);
        $parcelle = new Parcelle(); // Créez un objet Parcelle pour le test
        $parcelle->setNumber(1);
        $parcelle->setSize(100);
        $parcelle->setCreatedAt(new \DateTimeImmutable());
        $parcelle->setOwner($user);

        $tokenStorage->method('getToken')->willReturn($token);
        $token->method('getUser')->willReturn($user);
        $user->method('getParcelle')->willReturn($parcelle);
        $user->method('getFullname')->willReturn('John Doe');

        $controller = new ParcelleController();
        $container = $this->getContainer();
        $container->set('security.token_storage', $tokenStorage);
        $controller->setContainer($container);

        $response = $controller->index($parcelleRepository);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Ma parcelle', $response->getContent());
    }
}