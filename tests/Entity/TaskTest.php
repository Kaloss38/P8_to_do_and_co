<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Manager\TaskManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $taskRepository;

    private $userRepository;

    private $entityManager;

    /** @var TaskManager */
    private $entity;

    public function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entity = new TaskManager($this->taskRepository, $this->userRepository, $this->entityManager);
    }

    public function testCreateTask()
    {
        $task = new Task();
        $task->setTitle('Titre');
        $task->setContent('Contenu');

        $user = new User();
        $user->setUsername('username');
        $user->setEmail('email@email.com');
        $user->setPassword('secret');

        $expected = clone $task;

        $actual = $this->entity->createTask($task, $user);

        $expected->setAuthor($user);

        $this->assertEquals($expected, $actual);
    }

    public function testGetTasks()
    {
        $task[0] = new Task();
        $task[0]->setTitle('Titre');
        $task[0]->setContent('Contenu');
        $this->taskRepository->expects($this->any())->method("findAll")->willReturn($task);

        $expected[0] = clone $task[0];

        $actual = $this->entity->getTasks();

        $this->assertEquals($expected, $actual);
    }

    public function testDeleteOwnTask()
    {
        $task = new Task();
        $task->setTitle('Titre');
        $task->setContent('Contenu');

        $user = new User();
        $user->setUsername('username');
        $user->setEmail('email@email.com');
        $user->setPassword('secret');

        $task->setAuthor($user);

        $expected = clone $task;

        $actual = $this->entity->deleteTask($task, $user);

        $expected = null;

        $this->assertEquals($expected, $actual);
    }

    public function testDeleteTaskWithWrongUser()
    {
        $task = new Task();
        $task->setTitle('Titre');
        $task->setContent('Contenu');

        $user = new User();
        $user->setUsername('username');
        $user->setEmail('email@email.com');
        $user->setPassword('secret');

        $task->setAuthor($user);

        $user2 = new User();
        $user2->setUsername('username2');
        $user2->setEmail('email2@email.com');
        $user2->setPassword('secret');

        $actual = $this->entity->deleteTask($task, $user2);

        $this->assertFalse($actual);
    }

    public function testDeleteAnonymousTaskWithAdmin()
    {
        $task = new Task();
        $task->setTitle('Titre');
        $task->setContent('Contenu');

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('email@email.com');
        $userAdmin->setPassword('secret');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        

        $userAnonymous = new User();
        $userAnonymous->setUsername('anonymous');
        $userAnonymous->setEmail('email@email.com');
        $userAnonymous->setPassword('secret');
        

        $task->setAuthor($userAnonymous);

        $actual = $this->entity->deleteTaskAnonymous($task, $userAdmin, $userAnonymous);

        $this->assertTrue($actual);
    }
}
