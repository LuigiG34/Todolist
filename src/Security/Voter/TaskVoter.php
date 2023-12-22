<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        // Only vote on 'DELETE' actions for Task objects
        return $attribute === 'DELETE' && $subject instanceof Task;
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
        return $task->getUser() === $user || ($task->getUser() === null && in_array('ROLE_ADMIN', $user->getRoles()));
    }
}
