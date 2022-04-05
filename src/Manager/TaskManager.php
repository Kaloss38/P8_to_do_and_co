<?php

namespace App\Manager;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskManager
{
    private $entityManager;
    private $taskRepository;
    private $userRepository;

    public function __construct(
        TaskRepository $taskRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function createTask(Task $task, ?UserInterface $user)
    {
        $task->setAuthor($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function getTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function deleteTask(Task $task, ?UserInterface $user)
    {
        if ($task->getAuthor() === $user) {
            $task = null;
            return $task;
        }
        return false;
    }

    public function deleteTaskAnonymous(Task $task, ?UserInterface $userAdmin, ?UserInterface $userAnonymous)
    {
        if (in_array('ROLE_ADMIN', $userAdmin->getRoles()) && $task->getAuthor() === $userAnonymous) {
            return true;
        }
        return false;
    }
}