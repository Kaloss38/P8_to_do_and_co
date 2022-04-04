<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TaskControllerTest extends WebTestCase
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

    public function testListTasksWithoutLogin(): void
    {
        $this->client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateTaskWithoutLogin(): void
    {
        $this->client->request('GET', '/tasks/create');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testEditTaskWithoutLogin(): void
    {
        $this->client->request('GET', '/tasks/1/edit');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testToogleTaskWithoutLogin(): void
    {
        $this->client->request('GET', '/tasks/1/toggle');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testDeleteTaskWithoutLogin(): void
    {
        $this->client->request('GET', '/tasks/1/delete');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateTask()
    {
        $this->userLogin();

        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task' => [
                'title' => 'Une nouvelle tâche',
                'content' => 'pour tester testCreateTask()'
            ]
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }

        public function testEditTask()
    {
        $this->userLogin();

        $crawler = $this->client->request('GET', '/');

        $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');

        $crawler = $this->client->clickLink("La tâche d'un utilisateur classique");

        $this->assertSelectorTextContains('h1', "Modifier La tâche d'un utilisateur classique");

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => "La tâche d'un utilisateur classique",
            'task[content]' => "Elle n'est pas si difficile"
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.alert.alert-success');
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }
    
    public function testToggleTask()
    {
        $this->userLogin();

        $this->client->request('GET', '/');

        $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');

        $form = $crawler->filter('form')->selectButton('Marquer comme faite')->eq(1)->form();
        $crawler = $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('div.alert.alert-success');
    }
    
    // public function testDeleteTask()
    // {
    //     $this->userLogin();

    //     $this->client->request('GET', '/');

    //     $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

    //     $this->assertSelectorTextContains('h1', 'Liste des tâches');

    //     $form = $crawler->filter('form')->selectButton('Supprimer')->eq(1)->form();
    //     $crawler = $this->client->submit($form);

    //     $this->client->followRedirect();
    //     $this->assertSelectorExists('div.alert.alert-success');
    // }

    // public function testDeleteTaskWithAdminRole()
    // {
    //     $this->adminLogin();

    //     $this->client->request('GET', '/');

    //     $crawler = $this->client->clickLink('Consulter la liste des tâches à faire');

    //     $this->assertSelectorTextContains('h1', 'Liste des tâches');

    //     $form = $crawler->filter('form')->selectButton('Supprimer')->eq(1)->form();
    //     $crawler = $this->client->submit($form);

    //     $this->client->followRedirect();
    //     $this->assertSelectorExists('div.alert.alert-success');
    // }
    
}
