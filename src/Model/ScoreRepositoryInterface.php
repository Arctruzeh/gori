<?php

declare(strict_types=1);

namespace App\Model;

interface ScoreRepositoryInterface
{
    public function addScore(int $userId, int $score): bool;

    public function createRunScore(int $userId, int $score): int;

    public function updateRunScore(int $scoreId, int $userId, int $score): bool;

    /**
     * @return list<array<string, mixed>>
     */
    public function getHighScores(int $limit = 20): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function getUserHighScores(int $userId): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function getUserRecentScores(int $userId, int $limit = 10): array;

    public function getUserBestScore(int $userId): int;
}
