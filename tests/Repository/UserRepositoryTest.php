<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAnonymousUser(): void
    {
        self::bootKernel();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        $this->assertIsObject($user);
    }

    public function testFindAll(): void
    {
        self::bootKernel();

        $users = $this->entityManager->getRepository(User::class)->findAll();

        $this->assertGreaterThanOrEqual(4, $users);
    }

    public function testSearchByName()
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'anonymous'])
        ;

        $this->assertSame('anonymous@email.com', $user->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
