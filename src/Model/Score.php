<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final class Score
{
    public function __construct(
        private readonly PDO $pdo,
    ) {
    }

    public function addScore(int $userId, int $score): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO scores (user_id, score) VALUES (:user_id, :score)');
        return $stmt->execute(['user_id' => $userId, 'score' => $score]);
    }

    public function createRunScore(int $userId, int $score): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO scores (user_id, score) VALUES (:user_id, :score) RETURNING id'
        );
        $stmt->execute(['user_id' => $userId, 'score' => $score]);
        return (int) $stmt->fetchColumn();
    }

    public function updateRunScore(int $scoreId, int $userId, int $score): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE scores SET score = :score, created_at = CURRENT_TIMESTAMP WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            'id' => $scoreId,
            'user_id' => $userId,
            'score' => $score,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function getHighScores(int $limit = 20): array
    {
        $sql = '
            SELECT u.username, best.score, best.created_at
            FROM users u
            JOIN (
                SELECT DISTINCT ON (user_id) user_id, score, created_at
                FROM scores
                ORDER BY user_id, score DESC, created_at DESC
            ) best ON best.user_id = u.id
            ORDER BY best.score DESC, best.created_at ASC
            LIMIT :limit
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserHighScores(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM scores WHERE user_id = :user_id ORDER BY score DESC LIMIT 10');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getUserRecentScores(int $userId, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM scores WHERE user_id = :user_id ORDER BY created_at DESC, id DESC LIMIT :limit'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserBestScore(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT MAX(score) as max_score FROM scores WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();
        return (int) ($result['max_score'] ?? 0);
    }
}
