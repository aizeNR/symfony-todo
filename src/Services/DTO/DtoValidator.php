<?php

namespace App\Services\DTO;

use App\DTO\BaseDTO;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateDTO(BaseDTO $dto): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto);
    }
}