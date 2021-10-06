<?php

namespace App\Controller\Api\V1;

use App\DTO\PaginatorDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Helpers\ValidationErrorHelper;
use App\Repository\TaskRepository;
use App\Security\VoterCrud;
use App\UseCase\Task\CreateTaskAction;
use App\UseCase\Task\UpdateTaskAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Tag(name="Tasks")
 * @Route("/tasks", name="tasks.")
 */
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
     * @Route("", name="index", methods={"GET"})
     * @OA\Parameter(
     *     name="filter[taskTitle]",
     *     in="query",
     *     description="Filter by task title",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="filter[taskStatus]",
     *     in="query",
     *     description="Filter by task status. 0 - draft, 1 - progress, 2 - complete.",
     *     @OA\Schema(type="integer", enum={0, 1, 2})
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return list of tasks",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Task::class, groups={"list_task", "default"}))
     *     )
     * )
     */
    public function index(Request $request, TaskRepository $repository): JsonResponse
    {
        /**
         * @var User $user
         */
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
            ]);
    }

    /**
     * @Route("", name="store", methods={"POST"})
     * @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="aplication/json",
     *          @OA\Schema(ref=@Model(type=Task::class, groups={"create_task"}))
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Show created task",
     *     @OA\JsonContent(ref=@Model(type=Task::class, groups={"show_task", "default"})),
     * )
     */
    public function store(Request $request, CreateTaskAction $createTaskAction): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $taskDTO = new CreateTaskDTO( // need add validation to request
            $request->get('title', ''),
            $request->get('description'),
            $user,
            $request->get('tags', []),
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
     * @Route("/{id}", name="show", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Show task",
     *     @OA\JsonContent(ref=@Model(type=Task::class, groups={"show_task", "default"})),
     * )
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
     * @Route("/{id}", name="update", methods={"PUT"})
     * @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="aplication/json",
     *          @OA\Schema(ref=@Model(type=Task::class, groups={"create_task"}))
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Show created task",
     *     @OA\JsonContent(ref=@Model(type=Task::class, groups={"show_task", "default"})),
     * )
     */
    public function update(int $id, Request $request, UpdateTaskAction $updateTaskAction, TaskRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $repository->find($id);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(VoterCrud::EDIT, $task);

        $taskDTO = new UpdateTaskDTO( // need add validation to request
            $data['title'] ?? '',
            $data['description'] ?? null,
            $user,
            $data['tags'] ?? [],
            $data['status'] ?? 0
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
     * @Route ("/{id}", name="delete", methods={"DELETE"})
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