<?php

namespace App\UseCase\Task;

use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Repository\TagRepository;
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
    private TaskRepository $taskRepository;

    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;


    /**
     * @param EntityManagerInterface $entityManager
     * @param DtoValidator $validator
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DtoValidator     $validator,
        TaskRepository         $taskRepository,
        TagRepository $tagRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->taskRepository = $taskRepository;
        $this->tagRepository = $tagRepository;
    }

    public function execute(int $taskId, UpdateTaskDTO $taskDTO): Task
    {
        $this->validator->validateDTO($taskDTO);

        $task = $this->findTask($taskId);

        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setUser($taskDTO->getUser());
        $task->setStatus($taskDTO->getStatus());

        $tags = $this->tagRepository->findBy(['id' => $taskDTO->getTagIds()]);

        $task->addTag(...$tags);

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