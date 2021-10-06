<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    /**
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @param string[][] $context
     * @return JsonResponse
     */
    public function successResponse($data, int $code = 200, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }

    /**
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    public function errorResponse(array $data, int $code = 400, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }
}