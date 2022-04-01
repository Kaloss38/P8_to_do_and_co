<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    public const DELETE = 'delete';

    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $user): bool
    {
        if ($user === $task->getAuthor()) {
            return true;
        }

        if (
            $task->getAuthor() === $this->userRepo->findOneBy(['username' => 'anonymous'])
            && in_array("ROLE_ADMIN", $user->getRoles())
        ) {
            return true;
        }

        return false;
    }
}
