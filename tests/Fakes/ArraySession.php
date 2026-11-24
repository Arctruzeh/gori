<?php

declare(strict_types=1);

namespace App\Tests\Fakes;

use App\Http\SessionInterface;

final class ArraySession implements SessionInterface
{
    /** @var array<string, mixed> */
    public array $data = [];

    /** @var array<string, string> */
    public array $flash = [];

    public int $regenerateCount = 0;

    public int $readCount = 0;

    public function get(string $key, mixed $default = null): mixed
    {
        $this->readCount++;
        return $this->data[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function has(string $key): bool
    {
        $this->readCount++;
        return isset($this->data[$key]);
    }

    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    public function setFlash(string $key, string $message): void
    {
        $this->flash[$key] = $message;
    }

    public function getFlash(string $key): ?string
    {
        if (isset($this->flash[$key])) {
            $message = $this->flash[$key];
            unset($this->flash[$key]);
            return $message;
        }
        return null;
    }

    public function regenerateId(bool $deleteOldSession = true): void
    {
        $this->regenerateCount++;
    }
}
