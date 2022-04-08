<?php

namespace App\Manager;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    private $taskRepository;

    private $userRepository;

    private $entityManager;

    private $hasher;

    public function __construct(
        TaskRepository $taskRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        PasswordHasherInterface $hasher
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->hasher->hash($plainPassword);
    }

    public function createUser(?UserInterface $userAdmin, ?UserInterface $newUser)
    {
        if (in_array('ROLE_ADMIN', $userAdmin->getRoles())) {
            return $newUser;
        }

        return null;
    }

    public function getUsers()
    {
        return $this->userRepository->findAll();
    }
}