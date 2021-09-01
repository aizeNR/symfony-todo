<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class VoterCrud extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function getCustomAttribute(): array
    {
        return [
            self::VIEW,
            self::EDIT,
            self::DELETE,
        ];
    }

    abstract protected function canView($subject, $user): bool;

    abstract protected function canEdit($subject, $user): bool;

    abstract protected function canDelete($subject, $user): bool;
}