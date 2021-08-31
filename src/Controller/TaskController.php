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

class TaskController extends BaseController
{
    /**
     * @var ValidationErrorHelper
     */
    private $validationErrorHelper;

    public function __construct(ValidationErrorHelper $validationErrorHelper)
    {
        $this->validationErrorHelper = $validationErrorHelper;
    }

    /**
     * @Route("/tasks", name="task.index", methods={"GET"})
     */
    public function index(Request $request, TaskRepository $repository): JsonResponse
    {
        $tasks = $repository->paginate($request->get('page', 1));

        return $this->successResponse($tasks->getResults(), 200, [], ['groups' => 'show_task']);
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
            return $this->errorResponse($exception->getMessage(), 422);
        }

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }

    /**
     * @Route("/tasks/{id}", methods={"GET"})
     *
     * @param int $id
     * @param TaskRepository $repository
     * @return JsonResponse
     */
    public function show(int $id, TaskRepository $repository): JsonResponse
    {
        $task = $repository->find($id);

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
            return $this->errorResponse($exception->getMessage(), 422);
        }

        return $this->successResponse($task, 200, [], ['groups' => 'show_task']);
    }

    /**
     * @Route ("/tasks/{id}", methods={"DELETE"})
     *
     * @param int $id
     * @param TaskRepository $repository
     * @return JsonResponse
     */
    public function delete(int $id, TaskRepository $repository): JsonResponse
    {
        $task = $repository->find($id);

        if (is_null($task)) {
            return $this->errorResponse(
                ['errors' => [$this->validationErrorHelper->getMessageForNotFound(Task::class, $id)]],
                404
            );
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();

        return $this->successResponse([
            'status' => true
        ]);
    }
}