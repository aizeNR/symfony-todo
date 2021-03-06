<?php

namespace App\Helpers;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorHelper
{
    public function getPrettyErrors(ConstraintViolationListInterface $list): array
    {
        $prettyList = [];

        foreach ($list as $error){
            $prettyList[$error->getPropertyPath()][] = $error->getMessage();
        }

        return ['errors' => [$prettyList]];
    }

    public function getMessageForNotFound(string $entity, int $findKey): string
    {
        return "{$entity} with key '{$findKey}' not found!";
    }
}