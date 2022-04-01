<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
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
}
