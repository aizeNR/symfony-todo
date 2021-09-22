<?php

namespace App\Controller\Api\V1;

use App\DTO\User\CreateUserDTO;
use App\UseCase\User\CreateUserAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @Route ("/register", methods={"POST"})
     * @param Request $request
     * @param CreateUserAction $createUserAction
     * @return JsonResponse
     */
    public function register(Request $request, CreateUserAction $createUserAction): JsonResponse
    {
        $userDTO = new CreateUserDTO(
            $request->request->get('email', ''),
            $request->request->get('password', ''),
            $request->files->get('avatar')
        );

        $createUserAction->execute($userDTO);

        return $this->successResponse([], 204);
    }

    /**
     * @Route ("/me", methods={"GET"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->successResponse(
            $this->getUser(),
            200,
            [],
            [
                'groups' => [
                    'show_user'
                ]
            ]
        );
    }
}