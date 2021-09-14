<?php

namespace App\Listeners;

use App\Helpers\ValidationErrorHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @var ValidationErrorHelper
     */
    private $errorHelper;

    public function __construct(ValidationErrorHelper $errorHelper)
    {
        $this->errorHelper = $errorHelper;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();
        $message = 'Internal Server Error!';
        if ($exception instanceof ValidationFailedException) {
            $violations = $exception->getViolations();
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            $message = json_encode($this->errorHelper->getPrettyErrors($violations));
        } else if ($exception instanceof HttpExceptionInterface) {
            $response->headers->replace($exception->getHeaders());
            $response->setStatusCode($exception->getStatusCode());
            $message = $exception->getMessage();
        } else {
            $response->setStatusCode($exception->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = $exception->getMessage();
        }

        $response->setContent($message);

        $event->setResponse($response);
    }
}