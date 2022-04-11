<?php

namespace App\Tests\Controller;

use Generator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    public function adminLogin()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(["email" => 'admin@email.com']);

        $this->client->loginUser($testUser);
    }

    public function testListUsersWithoutLogin(): void
    {
        $this->client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/create');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditUserWithoutLogin(): void
    {
        $this->client->request('GET', '/users/83/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testViewUsersPagesWithSimpleUserLogin()
    {
        $this->userLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

        $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

        $userRepository = static::getContainer()->get(UserRepository::class);
        $lastUser = $userRepository->findOneBy([], ['id' => 'desc']);
        
        $this->client->request('GET', '/users/'.$lastUser->getId().'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testViewUsersPagesWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $lastUser = $userRepository->findOneBy([], ['id' => 'desc']);

        $this->client->request('GET', '/users/'.$lastUser->getId().'/edit');
        
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateUserWithAdminLogin()
    {
        $this->adminLogin();

        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer un utilisateur');

        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $form = $crawler->selectButton('Ajouter')->form([
            'user' => [
                'username' => 'utilisateur_de_test_'.rand(),
                'password' => [
                    'first' => 'secret',
                    'second' => 'secret'
                ],
                'email' => 'test'.rand().'@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);
        
        $this->assertResponseStatusCodeSame(200);
    }
    
    public function testEditUserWithAdminLogin()
    {
        $this->adminLogin();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $lastUser = $userRepository->findOneBy([], ['id' => 'desc']);

        $crawler = $this->client->request('GET', '/users/'.$lastUser->getId().'/edit');

        $this->assertSelectorTextContains('h1', 'Modifier '.$lastUser->getUsername());

        $form = $crawler->selectButton('Modifier')->form([
            'user' => [
                'username' => $lastUser->getUsername().'_modify_'.rand(),
                'password' => [
                    'first' => 'simple_user_2',
                    'second' => 'simple_user_2'
                ],
                'email' => $lastUser->getUsername().'_modify_'.rand().'@example.com',
                'roles_options' => 'ROLE_USER'
            ]
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(200);
    }

}
