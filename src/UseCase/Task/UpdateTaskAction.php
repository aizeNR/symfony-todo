<?php

namespace App\UseCase\Task;

use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Exception\Task\UpdateTaskException;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateTaskAction
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
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator,
        TaskRepository         $taskRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @throws UpdateTaskException
     */
    public function __invoke(int $taskId, UpdateTaskDTO $taskDTO): Task
    {
        $task = $this->taskRepository->find($taskId);

        if (is_null($task)) {
            throw new \InvalidArgumentException(); // TODO switch to custom
        }

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());

        $this->validateTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @throws UpdateTaskException
     */
    private function validateTask(Task $task): void
    {
        $errors = $this->validator->validate($task);

        if (count($errors) > 0) { // find a way, to handle it, and reform
            throw new UpdateTaskException($errors);
        }
    }
}