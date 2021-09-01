<?php

namespace App\Controller\Api\V1;

use App\DTO\User\CreateUserDTO;
use App\Entity\User;
use App\Services\UserService;
use App\UseCase\User\CreateUserAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @Route ("/register", methods={"POST"})
     * @param Request $request
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function register(Request $request, UserService $userService): JsonResponse
    {
        $userDTO = new CreateUserDTO(
            $request->request->get('email'),
            $request->request->get('password')
        );

        $userService->createUser($userDTO);

        return $this->successResponse([], 204);
    }
}