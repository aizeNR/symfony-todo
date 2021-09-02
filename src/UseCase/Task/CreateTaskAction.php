<?php

namespace App\UseCase\Task;

use App\DTO\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Services\DTO\DtoValidator;
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
     * @param DtoValidator $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DtoValidator $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     */
    public function execute(CreateTaskDTO $taskDTO): Task
    {
        $errors = $this->validator->validateDTO($taskDTO);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException();
        }

        $task = new Task();

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($taskDTO->getUser());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}