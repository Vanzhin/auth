<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Webmozart\Assert\Assert;

readonly class UserFetcher implements UserFetcherInterface
{
    public function __construct(private Security $security, private UserRepositoryInterface $userRepository)
    {
    }

    public function getAuthUser(): AuthUserInterface
    {
        /** @var AuthUserInterface $user */
        $user = $this->security->getUser();

        Assert::notNull($user, 'Current user not found.');
        Assert::isInstanceOf($user, AuthUserInterface::class, 'Invalid user type.');

        return $user;
    }

    public function getAuthUserId(): string
    {
        return $this->getAuthUser()->getUlid();
    }

    public function getUserById(string $userId): AuthUserInterface
    {
        $user = $this->userRepository->getByUlid($userId);
        Assert::notNull($user, 'No user found.');

        return $user;
    }
}
