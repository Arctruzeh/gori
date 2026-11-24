<?php

declare(strict_types=1);

namespace App\Http;

interface SessionInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function has(string $key): bool;

    public function remove(string $key): void;

    /**
     * @return array<string, mixed>
     */
    public function all(): array;

    public function setFlash(string $key, string $message): void;

    public function getFlash(string $key): ?string;

    public function regenerateId(bool $deleteOldSession = true): void;
}
