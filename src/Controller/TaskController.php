<?php

namespace App\Controller;

use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Exception\Task\CreateTaskException;
use App\Helpers\ValidationErrorHelper;
use App\Repository\TaskRepository;
use App\UseCase\Task\CreateTaskAction;
use App\UseCase\Task\UpdateTaskAction;
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
        } catch (CreateTaskException $exception) { // move to global handler
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
    public function update(int $id, Request $request, UpdateTaskAction $updateTaskAction): JsonResponse
    {
        $taskDTO = new UpdateTaskDTO(
            $request->get('title'),
            $request->get('description')
        );

        try {
            $task = $updateTaskAction($id, $taskDTO);
        } catch (CreateTaskException $exception) { // move to global handler
            return $this->successResponse($exception->getMessage(), 422);
        }

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }
}