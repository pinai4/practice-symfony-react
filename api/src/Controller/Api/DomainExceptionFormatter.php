<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\ErrorHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainExceptionFormatter implements EventSubscriberInterface
{
    private ErrorHandler $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof \DomainException) {
            return;
        }

        $route = (string) $request->attributes->get('_route');
        if (!str_starts_with($route, 'api.')) {
            return;
        }

        $this->errors->handle($exception);

        $event->setResponse(
            new JsonResponse(['message' => $exception->getMessage()], 409)
        );
    }
}
