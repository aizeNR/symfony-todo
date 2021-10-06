<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    /**
     * @param array|null|object $data
     * @param int $code
     * @param string[][] $context
     */
    public function successResponse($data, int $code = 200, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }

    /**
     * @param string[][] $data
     * @param int $code
     */
    public function errorResponse(array $data, int $code = 400, $headers = [], $context = []): JsonResponse
    {
        return $this->json($data, $code, $headers, $context);
    }
}