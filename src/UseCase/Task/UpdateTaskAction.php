<?php

namespace App\UseCase\Task;

use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Exception\Task\UpdateTaskException;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator,
        TaskRepository         $taskRepository,
        UserRepository         $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UpdateTaskException
     */
    public function __invoke(int $taskId, UpdateTaskDTO $taskDTO): Task
    {
        $task = $this->findTask($taskId);
        $user = $this->findUser($taskDTO->getUserId());

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($user);

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

    private function findTask($taskId): Task
    {
        $task = $this->taskRepository->find($taskId);

        if (is_null($task)) {
            throw new InvalidArgumentException(); // TODO switch to custom
        }

        return $task;
    }

    private function findUser($userId): User
    {
        $user = $this->userRepository->find($userId);

        if (is_null($user)) {
            throw new InvalidArgumentException(); // TODO switch to custom
        }

        return $user;
    }
}