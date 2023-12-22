<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // Only vote on 'self::DELETE' actions for Task objects
        return $attribute === self::DELETE && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // If the user is not logged in, deny permission
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                // Check if the task is created by the user or if the task has no user and the current user is an admin
                return $this->canDelete($task, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $user): bool
    {
        // Check if the task is associated with a user
        if ($task->getUser() !== null) {
            // Allow deletion only if the task is associated with the current user
            return $task->getUser() === $user;
        } else {
            // If the task is not associated with any user, allow deletion only for admin users
            return in_array('ROLE_ADMIN', $user->getRoles());
        }
    }
}
