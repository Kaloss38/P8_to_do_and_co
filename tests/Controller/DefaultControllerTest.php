<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    
    public function userLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'user@email.com']);

        $this->client->loginUser($testUser);
    }

    public function testIndexWithoutLogin(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testViewHomepage()
    {
        $this->userLogin();

        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}
