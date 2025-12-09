<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        if ($exception instanceof BadRequestHttpException) {
            $decoded = json_decode($exception->getMessage(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $event->setResponse(new JsonResponse($decoded, $statusCode));
                return;
            }
        }

        $event->setResponse(new JsonResponse([
            'error' => true,
            'message' => $exception->getMessage(),
        ], $statusCode));
    }
}
