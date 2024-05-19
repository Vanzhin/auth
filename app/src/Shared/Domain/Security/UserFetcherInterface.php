<?php

declare(strict_types=1);

namespace App\Shared\Domain\Security;

interface UserFetcherInterface
{
    public function getAuthUser(): AuthUserInterface;

    public function getAuthUserId(): string;

    public function getUserById(string $userId): AuthUserInterface;
}
