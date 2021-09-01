<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    public function successResponse($data, $code = 200, $headers = [], $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }

    public function errorResponse($data, $code = 400, $headers = [], $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }
}