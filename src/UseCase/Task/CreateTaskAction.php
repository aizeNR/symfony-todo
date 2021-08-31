<?php

namespace App\UseCase\Task;

use App\DTO\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Exception\Task\CreateTaskException;
use App\Repository\UserRepository;
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
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CreateTaskException
     */
    public function __invoke(CreateTaskDTO $taskDTO): Task // Create DTO
    {
        $user = $this->userRepository->find($taskDTO->getUserId()); // add check exists user

        $task = new Task();

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($user);

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