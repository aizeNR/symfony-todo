<?php

namespace App\UseCase\Task;

use App\DTO\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Repository\TagRepository;
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
    private TagRepository $tagRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DtoValidator $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository,
        DtoValidator $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->tagRepository = $tagRepository;
    }

    /**
     */
    public function execute(CreateTaskDTO $taskDTO): Task
    {
        $this->validator->validateDTO($taskDTO);

        $task = new Task();

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
}