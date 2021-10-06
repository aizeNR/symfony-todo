<?php

namespace App\Entity\Dictionary;

use LogicException;

class TaskStatusDictionary
{
    const DRAFT = 0;
    const PROGRESS = 1;
    const COMPLETE = 2;

    public static function getFlipDictionary(): array
    {
        $availableStatuses = static::getDictionary();

        return array_values(array_flip($availableStatuses));
    }

    public static function getDictionary(): array
    {
        return [
            static::DRAFT => 'draft',
            static::PROGRESS => 'progress',
            static::COMPLETE => 'complete'
        ];
    }

    public static function getPrettyStatus(int $key): string
    {
        $statuses = static::getDictionary();

        if (!isset($statuses[$key])) {
            throw new LogicException("Status doesn't exists!");
        }

        return $statuses[$key];
    }
}