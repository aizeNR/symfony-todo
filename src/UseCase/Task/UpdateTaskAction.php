<?php

namespace App\UseCase\Task;

use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\DTO\DtoValidator;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
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
     * @param DtoValidator $validator
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DtoValidator     $validator,
        TaskRepository         $taskRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->taskRepository = $taskRepository;
    }

    public function execute(int $taskId, UpdateTaskDTO $taskDTO): Task
    {
        $errors = $this->validator->validateDTO($taskDTO);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException();
        }

        $task = $this->findTask($taskId);

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($taskDTO->getUser());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    private function findTask($taskId): Task
    {
        $task = $this->taskRepository->find($taskId);

        if (is_null($task)) {
            throw new InvalidArgumentException(); // TODO switch to custom
        }

        return $task;
    }
}