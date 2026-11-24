<?php

declare(strict_types=1);

namespace App\Model;

interface UserRepositoryInterface
{
    public function create(string $username, string $password): bool;

    /**
     * @return array<string, mixed>|null
     */
    public function findByUsername(string $username): ?array;

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array;

    public function verifyPassword(string $password, string $hash): bool;
}
