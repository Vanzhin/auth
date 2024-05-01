<?php
declare(strict_types=1);


namespace App\Users\Domain\Entity;

use App\Shared\Domain\Service\UlidService;

class User
{
    private string $ulid;

    public function __construct(
        private readonly string  $email,
        private readonly ?string $password,

    )
    {
        $this->ulid = UlidService::generate();
    }

    public function getUlid(): string
    {
        return $this->ulid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

}