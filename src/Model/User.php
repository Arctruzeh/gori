<?php

namespace App\Model;

use App\Database\Connection;
use PDO;

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function create(string $username, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :hash)");

        try {
            return $stmt->execute(['username' => $username, 'hash' => $hash]);
        } catch (\PDOException $e) {
            // Likely duplicate username
            return false;
        }
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
