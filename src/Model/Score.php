<?php

namespace App\Model;

use App\Database\Connection;
use PDO;

class Score
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function addScore(int $userId, int $score): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO scores (user_id, score) VALUES (:user_id, :score)");
        return $stmt->execute(['user_id' => $userId, 'score' => $score]);
    }

    public function getHighScores(int $limit = 10): array
    {
        $sql = "
            SELECT u.username, s.score, s.created_at
            FROM scores s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.score DESC
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserHighScores(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM scores WHERE user_id = :user_id ORDER BY score DESC LIMIT 10");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getUserBestScore(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT MAX(score) as max_score FROM scores WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['max_score'] ?? 0;
    }
}
