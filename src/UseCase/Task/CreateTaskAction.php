<?php

namespace App\UseCase\Task;

use App\DTO\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Exception\Task\CreateTaskException;
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
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws CreateTaskException
     */
    public function __invoke(CreateTaskDTO $taskDTO): Task
    {
        $task = new Task();

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($taskDTO->getUser());

        $this->validateTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @throws CreateTaskException
     */
    private function validateTask(Task $task): void
    {
        $errors = $this->validator->validate($task);

        if (count($errors) > 0) { // find a way, to handle it, and reform
            throw new CreateTaskException($errors);
        }
    }
}