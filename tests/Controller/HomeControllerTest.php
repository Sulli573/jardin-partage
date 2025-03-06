<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase{
    public function testIndex() {
        #Création de faux client 
        $client = static::createClient();
        #Créer un crawler pour parcourir le contenu d'une page web 
        $crawler = $client->request("GET","/");

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h1","dzddzzdzdzddzd");
    }
}