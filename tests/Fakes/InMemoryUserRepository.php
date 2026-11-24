<?php

declare(strict_types=1);

namespace App\Tests\Fakes;

use App\Model\UserRepositoryInterface;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $users = [];

    private int $nextId = 1;

    public function create(string $username, string $password): bool
    {
        if ($this->findByUsername($username) !== null) {
            return false;
        }

        $id = $this->nextId++;
        $this->users[$id] = [
            'id' => $id,
            'username' => $username,
            'password_hash' => \password_hash($password, PASSWORD_BCRYPT),
            'created_at' => '2026-11-24 12:00:00',
        ];

        return true;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByUsername(string $username): ?array
    {
        foreach ($this->users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        return $this->users[$id] ?? null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }
}
