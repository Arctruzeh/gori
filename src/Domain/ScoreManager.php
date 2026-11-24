<?php

declare(strict_types=1);

namespace App\Domain;

use App\Http\Session;
use App\Model\Score;

final class ScoreManager
{
    public function __construct(
        private readonly Session $session,
        private readonly Score $scoreModel,
    ) {
        if (!$this->session->has('score')) {
            $this->session->set('score', 0);
        }
        if (!$this->session->has('streak')) {
            $this->session->set('streak', 0);
        }

        if ($this->session->has('user_id')) {
            $dbHigh = $this->scoreModel->getUserBestScore((int) $this->session->get('user_id'));
            $currentScore = (int) $this->session->get('score', 0);
            $this->session->set('highscore', \max($dbHigh, $currentScore));
        } elseif (!$this->session->has('highscore')) {
            $this->session->set('highscore', 0);
        }
    }

    public function getScore(): int
    {
        return (int) $this->session->get('score', 0);
    }

    public function getStreak(): int
    {
        return (int) $this->session->get('streak', 0);
    }

    public function recordSuccess(): int
    {
        $streak = $this->getStreak() + 1;
        $this->session->set('streak', $streak);

        $points = 10 + ($streak * 2);
        $score = $this->getScore() + $points;
        $this->session->set('score', $score);

        $this->persistRunScore($score);
        $this->updateHighScore();

        return $points;
    }

    public function recordFailure(): void
    {
        $this->session->set('streak', 0);
    }

    public function resetGame(): void
    {
        $this->session->set('score', 0);
        $this->session->set('streak', 0);
        $this->session->remove('current_run_score_id');
    }

    public function getRank(): string
    {
        return Rank::forScore($this->getScore());
    }

    public function getHighScore(): int
    {
        return (int) $this->session->get('highscore', 0);
    }

    private function persistRunScore(int $score): void
    {
        if (!$this->session->has('user_id')) {
            return;
        }

        $userId = (int) $this->session->get('user_id');
        $runId = $this->session->get('current_run_score_id');

        if ($runId !== null && $this->scoreModel->updateRunScore((int) $runId, $userId, $score)) {
            return;
        }

        $newRunId = $this->scoreModel->createRunScore($userId, $score);
        $this->session->set('current_run_score_id', $newRunId);
    }

    private function updateHighScore(): void
    {
        $current = $this->getScore();
        $high = $this->getHighScore();

        if ($current > $high) {
            $this->session->set('highscore', $current);
        }
    }
}
