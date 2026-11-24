<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\ScoreManager;
use App\Tests\Fakes\ArraySession;
use App\Tests\Fakes\InMemoryScoreRepository;
use PHPUnit\Framework\TestCase;

final class ScoreManagerTest extends TestCase
{
    public function testConstructorHasNoSessionOrDatabaseSideEffects(): void
    {
        $session = new ArraySession();
        $scores = new InMemoryScoreRepository();

        new ScoreManager($session, $scores);

        self::assertSame(0, $session->readCount);
        self::assertSame([], $session->data);
        self::assertSame(0, $scores->queryCount);
    }

    public function testGuestStreakScoringAndFailureReset(): void
    {
        $session = new ArraySession();
        $scores = new InMemoryScoreRepository();
        $manager = new ScoreManager($session, $scores);

        // Win 1: streak=1, points = 10 + 2 = 12
        self::assertSame(12, $manager->recordSuccess());
        self::assertSame(12, $manager->getScore());
        self::assertSame(1, $manager->getStreak());
        self::assertSame(12, $manager->getHighScore());

        // Win 2: streak=2, points = 10 + 4 = 14, total=26
        self::assertSame(14, $manager->recordSuccess());
        self::assertSame(26, $manager->getScore());
        self::assertSame(2, $manager->getStreak());
        self::assertSame(26, $manager->getHighScore());

        // Failure resets streak only; score & high score remain
        $manager->recordFailure();
        self::assertSame(0, $manager->getStreak());
        self::assertSame(26, $manager->getScore());
        self::assertSame(26, $manager->getHighScore());

        // Guests do not persist to DB
        self::assertSame(0, $scores->queryCount);
    }

    public function testAuthenticatedRunPersistenceAndReset(): void
    {
        $session = new ArraySession();
        $session->set('user_id', 7);

        $scores = new InMemoryScoreRepository();
        $scores->addScore(7, 20);
        $scores->queryCount = 0;

        $manager = new ScoreManager($session, $scores);
        self::assertSame(20, $manager->getHighScore());

        // First win creates a run row (score 12)
        $manager->recordSuccess();
        $runId = $session->get('current_run_score_id');
        self::assertIsInt($runId);
        self::assertSame(12, $scores->scores[$runId]['score']);
        self::assertSame(20, $manager->getHighScore());

        // Second win updates the same run row (score 26 > 20)
        $manager->recordSuccess();
        self::assertSame($runId, $session->get('current_run_score_id'));
        self::assertSame(26, $scores->scores[$runId]['score']);
        self::assertSame(26, $manager->getHighScore());

        // Reset clears current_run_score_id so next win starts a new run row
        $manager->resetGame();
        self::assertSame(0, $manager->getScore());
        self::assertSame(0, $manager->getStreak());
        self::assertFalse($session->has('current_run_score_id'));

        $manager->recordSuccess();
        $newRunId = $session->get('current_run_score_id');
        self::assertNotSame($runId, $newRunId);
        self::assertSame(12, $scores->scores[$newRunId]['score']);
    }
}
