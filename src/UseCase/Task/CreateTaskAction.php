<?php

namespace App\UseCase\Task;

use App\Entity\Task;
use App\Helpers\ValidationErrorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTaskAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ValidationErrorHelper
     */
    private $errorHelper;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        ValidationErrorHelper $errorHelper
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->errorHelper = $errorHelper;
    }

    public function __invoke(TaskDTO $taskDTO) // Create DTO
    {
        $task = new Task();

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDesctription());

        $this->validateTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
        // TODO: Implement __invoke() method.
    }

    private function validateTask(Task $task): void
    {
        $errors = $this->validator->validate($task);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException(); // Change to custom exception, and pretty it
        }
    }
}