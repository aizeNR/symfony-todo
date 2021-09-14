<?php

namespace App\Services\DTO;

use App\DTO\BaseDTO;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
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

    /**
     * @param BaseDTO $dto
     * @throws ValidationFailedException
     */
    public function validateDTO(BaseDTO $dto): void
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ValidationFailedException($dto, $errors);
        }
    }
}