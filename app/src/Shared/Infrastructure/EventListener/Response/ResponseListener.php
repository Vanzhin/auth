<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener\Response;

use App\Shared\Domain\VO\ResponseData;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
    public const MIME_JSON = 'application/json';

    public function __construct()
    {
    }

    #[AsEventListener(priority: 200)]
    public function onKernelException(ResponseEvent $event): void
    {
        $header = $event->getResponse()->headers->get('Content-Type');
        $statusCode = $event->getResponse()->getStatusCode();

        if (self::MIME_JSON === $header) {
            $response = new JsonResponse();
            $response->setStatusCode($statusCode);
            $data = $this->buildResponseData($event->getResponse());
            $response->setData($data);
            $event->setResponse($response);
        }
    }

    private function buildResponseData(Response $response): ResponseData
    {
        if ($response->getStatusCode() < 400) {
            $result = new ResponseData(
                'success',
                $response->getStatusCode(),
                json_decode($response->getContent(), true),
                null
            );
        } else {
            $result = new ResponseData(
                'error',
                $response->getStatusCode(),
                null,
                $this->getMessageFromContent($response->getContent())
            );
        }

        return $result;
    }

    private function getMessageFromContent(string $jsonString): ?string
    {
        $data = json_decode($jsonString, true);

        return $data['message'] ?? null;
    }
}
