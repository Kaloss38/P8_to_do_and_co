<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
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

    public function testFindAll(): void
    {
        self::bootKernel();

        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        $this->assertGreaterThanOrEqual(6, $tasks);
    }

    public function testSearchByName()
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'Première tâche'])
        ;

        $this->assertSame("J'appartiens à l'utilisateur anonyme", $task->getContent());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
