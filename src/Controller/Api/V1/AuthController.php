<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @Route ("/register", methods={"POST"})
     * @param Request $request
     */
    public function register(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $em->persist($user);
        $em->flush();

        return $this->successResponse([], 204);
    }
}