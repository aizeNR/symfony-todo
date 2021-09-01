<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends VoterCrud
{
    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, $this->getCustomAttribute())) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($task, $user);
            case self::EDIT:
                return $this->canEdit($task, $user);
        }
    }

    protected function canView($task, $user): bool
    {
        if ($this->canEdit($task, $user)) {
            return true;
        }

        // its example ^_^
        return false;
    }

    protected function canEdit($task, $user): bool
    {
        /** @var Task $task */
        return $task->getUser() === $user;
    }

    protected function canDelete($task, $user): bool
    {
        if ($this->canEdit($task, $user)) {
            return true;
        }

        // its example ^_^
        return false;
    }
}