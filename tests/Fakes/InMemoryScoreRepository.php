<?php

declare(strict_types=1);

namespace App\Tests\Fakes;

use App\Model\ScoreRepositoryInterface;

final class InMemoryScoreRepository implements ScoreRepositoryInterface
{
    /** @var array<int, array{id: int, user_id: int, score: int, created_at: string}> */
    public array $scores = [];

    public int $queryCount = 0;

    private int $nextId = 1;

    public function __construct(
        private readonly ?InMemoryUserRepository $users = null,
    ) {
    }

    public function addScore(int $userId, int $score): bool
    {
        $this->queryCount++;
        $id = $this->nextId++;
        $this->scores[$id] = [
            'id' => $id,
            'user_id' => $userId,
            'score' => $score,
            'created_at' => '2026-11-24 12:00:00',
        ];
        return true;
    }

    public function createRunScore(int $userId, int $score): int
    {
        $this->queryCount++;
        $id = $this->nextId++;
        $this->scores[$id] = [
            'id' => $id,
            'user_id' => $userId,
            'score' => $score,
            'created_at' => '2026-11-24 12:00:00',
        ];
        return $id;
    }

    public function updateRunScore(int $scoreId, int $userId, int $score): bool
    {
        $this->queryCount++;
        if (!isset($this->scores[$scoreId]) || $this->scores[$scoreId]['user_id'] !== $userId) {
            return false;
        }
        $this->scores[$scoreId]['score'] = $score;
        return true;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getHighScores(int $limit = 20): array
    {
        $this->queryCount++;
        $bestByUser = [];

        foreach ($this->scores as $row) {
            $uid = $row['user_id'];
            if (!isset($bestByUser[$uid]) || $row['score'] > $bestByUser[$uid]['score']) {
                $user = $this->users?->findById($uid);
                $bestByUser[$uid] = [
                    'username' => $user['username'] ?? "user_{$uid}",
                    'score' => $row['score'],
                    'created_at' => $row['created_at'],
                ];
            }
        }

        $list = \array_values($bestByUser);
        \usort($list, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        return \array_slice($list, 0, $limit);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getUserHighScores(int $userId): array
    {
        $this->queryCount++;
        $rows = \array_values(\array_filter(
            $this->scores,
            static fn (array $row): bool => $row['user_id'] === $userId
        ));
        \usort($rows, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);
        return \array_slice($rows, 0, 10);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getUserRecentScores(int $userId, int $limit = 10): array
    {
        $this->queryCount++;
        $rows = \array_values(\array_filter(
            $this->scores,
            static fn (array $row): bool => $row['user_id'] === $userId
        ));
        \usort($rows, static fn (array $a, array $b): int => $b['id'] <=> $a['id']);
        return \array_slice($rows, 0, $limit);
    }

    public function getUserBestScore(int $userId): int
    {
        $this->queryCount++;
        $best = 0;
        foreach ($this->scores as $row) {
            if ($row['user_id'] === $userId && $row['score'] > $best) {
                $best = $row['score'];
            }
        }
        return $best;
    }
}
