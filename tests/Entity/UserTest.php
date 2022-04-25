<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $taskRepository;

    private $userRepository;

    private $entityManager;

    private $hasher;

    private $user;

    /** @var UserManager */
    private $entity;

    public function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hasher = $this->createMock(PasswordHasherInterface::class);
        $this->entity = new UserManager(
            $this->taskRepository,
            $this->userRepository,
            $this->entityManager,
            $this->hasher
        );
        $this->user = new User();
    }

    public function testId(): void
    {
        $this->assertNull($this->user->getId());
    }

    public function testUsername(): void
    {
        $this->user->setUsername('name');
        $this->assertSame('name', $this->user->getUsername());
    }

    public function testEmail(): void
    {
        $this->user->setEmail('name@name.fr');
        $this->assertSame('name@name.fr', $this->user->getEmail());
    }

    public function testPassword(): void
    {
        $this->user->setPassword('password');
        $this->assertSame('password', $this->user->getPassword());
    }


    public function testUserHasRoles()
    {
        $user = new User();
        $user->setUsername('username');
        $user->setEmail('email@email.com');
        $user->setPassword('secret');

        $this->assertIsArray($user->getRoles());
    }

    public function testCreateUser()
    {
        $userAdmin = new User();
        $userAdmin->setUsername('username');
        $userAdmin->setEmail('email@email.com');
        $userAdmin->setPassword($this->entity->hashPassword('secret'));
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $newUser = new User();
        $newUser->setUsername('usernameNew');
        $newUser->setEmail("newuser@email.com");
        $newUser->setPassword($this->entity->hashPassword('secret'));

        $expected = clone $newUser;

        $actual = $this->entity->createUser($userAdmin, $newUser);

        $this->assertEquals($expected, $actual);
    }

    public function testGetUsers()
    {
        $user[0] = new User();
        $user[0]->setUsername('username');
        $user[0]->setEmail("user@email.com");
        $user[0]->setPassword($this->entity->hashPassword('secret'));
        
        $this->userRepository->expects($this->any())->method("findAll")->willReturn($user);

        $expected[0] = clone $user[0];

        $actual = $this->entity->getUsers();

        $this->assertEquals($expected, $actual);
    }

}
