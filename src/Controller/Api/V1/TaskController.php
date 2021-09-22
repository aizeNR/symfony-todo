<?php

namespace App\Controller\Api\V1;

use App\DTO\PaginatorDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Helpers\ValidationErrorHelper;
use App\Repository\TaskRepository;
use App\Security\VoterCrud;
use App\UseCase\Task\CreateTaskAction;
use App\UseCase\Task\UpdateTaskAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TaskController extends BaseController
{
    /**
     * @var ValidationErrorHelper
     */
    private ValidationErrorHelper $validationErrorHelper;

    public function __construct(ValidationErrorHelper $validationErrorHelper)
    {
        $this->validationErrorHelper = $validationErrorHelper;
    }

    /**
     * @Route("/tasks", name="task.index", methods={"GET"})
     */
    public function index(Request $request, TaskRepository $repository): JsonResponse
    {
        $user = $this->getUser();

        $filterDTO = new TaskFilterDTO($request->get('filter', []));
        $paginatorDTO = new PaginatorDTO(
            (int)$request->get('page', 1),
            (int) $request->get('limit', 10),
        );

        $tasks = $repository->getPaginateTasksForUser($user, $filterDTO, $paginatorDTO);

        return $this->successResponse(
            $tasks->getResults(),
            200,
            [],
            [
                AbstractNormalizer::GROUPS => [
                    'list_task',
                    'default'
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'user'
                ]
            ]);
    }

    /**
     * @Route("/tasks", name="task.store", methods={"POST"})
     */
    public function store(Request $request, CreateTaskAction $createTaskAction): JsonResponse
    {
        $user = $this->getUser();

        $taskDTO = new CreateTaskDTO( // need add validation to request
            $request->get('title', ''),
            $request->get('description', ''),
            $user,
            $request->get('status', 0)
        );

        $task = $createTaskAction->execute($taskDTO);

        return $this->successResponse(
            $task,
            200,
            [],
            [
                AbstractNormalizer::GROUPS => [
                    'show_task',
                    'default'
                ]
            ]);
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

        $this->denyAccessUnlessGranted(VoterCrud::VIEW, $task);

        if (is_null($task)) {
            return $this->errorResponse(
                ['errors' => [$this->validationErrorHelper->getMessageForNotFound(Task::class, $id)]],
                404
            );
        }

        return $this->successResponse(
            $task,
            200,
            [],
            [
                AbstractNormalizer::GROUPS => [
                    'show_task',
                    'default'
                ]
            ]);
    }

    /**
     * @Route("/tasks/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request, UpdateTaskAction $updateTaskAction, TaskRepository $repository): JsonResponse
    {
        $task = $repository->find($id);
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(VoterCrud::EDIT, $task);

        $taskDTO = new UpdateTaskDTO( // need add validation to request
            $request->get('title', ''),
            $request->get('description', ''),
            $user,
            $request->get('status', 0)
        );

        $task = $updateTaskAction->execute($id, $taskDTO);

        return $this->successResponse(
            $task,
            200,
            [],
            [
                AbstractNormalizer::GROUPS => [
                    'show_task',
                    'default'
                ]
            ]);
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

        $this->denyAccessUnlessGranted(VoterCrud::DELETE, $task);

        if (is_null($task)) {
            return $this->errorResponse(
                ['errors' => [$this->validationErrorHelper->getMessageForNotFound(Task::class, $id)]],
                404
            );
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();

        return $this->successResponse([], 204);
    }
}