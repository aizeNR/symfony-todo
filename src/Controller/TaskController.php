<?php

namespace App\Controller;

use App\Entity\Task;
use App\Helpers\ValidationErrorHelper;
use App\Repository\TaskRepository;
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
        ValidatorInterface $validator,
        ValidationErrorHelper $validationErrorHelper,
        TaskRepository $repository
    ) {
        $this->validator = $validator;
        $this->validationErrorHelper = $validationErrorHelper;
        $this->taskRepository = $repository;
    }

    /**
     * @Route("/tasks", name="task.index")
     */
    public function index()
    {
        return $this->successResponse(
            $this->taskRepository->findAll(),
            200,
            [],
            ['groups' => 'show_task']
        );
    }

    /**
     * @Route("/tasks", name="task.store", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
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


        return $this->successResponse(
            $task,
            200,
            [],
            ['groups' => 'show_task']
        );
    }
}