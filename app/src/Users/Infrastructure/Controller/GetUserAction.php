<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Domain\Security\UserFetcherInterface;
use App\Users\Application\Service\AccessControl\UserAccessControl;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/{ulid}', methods: ['GET'])]
readonly class GetUserAction
{
    public function __construct(private UserAccessControl $accessControl,
        private UserFetcherInterface $userFetcher)
    {
    }

    public function __invoke(string $ulid): JsonResponse
    {
        $authUserId = $this->userFetcher->getAuthUser()->getUlid();
        if (!$this->accessControl->canView($ulid, $authUserId)) {
            throw new AccessDeniedException();
        }
        $user = $this->userFetcher->getUserById($ulid);

        return new JsonResponse([
            'id' => $user->getUlid(),
            'roles' => $user->getRoles(),
            'email' => $user->getEmail(),
        ]);
    }
}
