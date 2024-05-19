<?php

declare(strict_types=1);

namespace App\Shared\Domain\VO;

readonly class ResponseData implements \JsonSerializable
{
    public function __construct(
        private string $result,
        private int $status,
        private mixed $data,
        private ?string $message,
    ) {
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
