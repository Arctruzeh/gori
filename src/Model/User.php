<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;

final class User
{
    public function __construct(
        private readonly PDO $pdo,
    ) {
    }

    public function create(string $username, string $password): bool
    {
        $hash = \password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :hash)');

        try {
            return $stmt->execute(['username' => $username, 'hash' => $hash]);
        } catch (PDOException) {
            return false;
        }
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();
        return \is_array($row) ? $row : null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return \is_array($row) ? $row : null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }
}
