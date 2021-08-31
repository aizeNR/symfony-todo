<?php

namespace App\Controller;

use App\DTO\Task\CreateTaskDTO;
use App\Entity\Task;
use App\Exception\CreateTaskException;
use App\Helpers\ValidationErrorHelper;
use App\Repository\TaskRepository;
use App\UseCase\Task\CreateTaskAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends BaseController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ValidationErrorHelper
     */
    private $validationErrorHelper;
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(
        ValidatorInterface    $validator,
        ValidationErrorHelper $validationErrorHelper,
        TaskRepository        $repository
    )
    {
        $this->validator = $validator;
        $this->validationErrorHelper = $validationErrorHelper;
        $this->taskRepository = $repository;
    }

    /**
     * @Route("/tasks", name="task.index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->successResponse($this->taskRepository->findAll(), 200, [], ['groups' => 'show_task']);
    }

    /**
     * @Route("/tasks", name="task.store", methods={"POST"})
     */
    public function store(Request $request, CreateTaskAction $createTaskAction): JsonResponse
    {
        $taskDTO = new CreateTaskDTO(
            $request->get('title'),
            $request->get('description')
        );

        try {
            $task = $createTaskAction($taskDTO);
        } catch (CreateTaskException $exception) { // move to glo
            return $this->successResponse($exception->getMessage(), 422);
        }

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }

    /**
     * @Route("/tasks/{id}", methods={"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);

        if (is_null($task)) {
            return $this->errorResponse(
                ['errors' => [$this->validationErrorHelper->getMessageForNotFound(Task::class, $id)]],
                404
            );
        }

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }

    /**
     * @Route("/tasks/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $this->taskRepository->find($id);

        if (is_null($task)) {
            return $this->errorResponse(
                ['errors' => [$this->validationErrorHelper->getMessageForNotFound(Task::class, $id)]],
                404
            );
        }

        $task->setTitle($request->get('title'));
        $task->setDescription($request->get('description'));

        $errors = $this->validator->validate($task);

        if (count($errors) > 0) {
            return $this->errorResponse(
                $this->validationErrorHelper->getPrettyErrors($errors),
                422
            );
        }

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }
}