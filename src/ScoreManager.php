<?php

namespace App;

use App\Http\Session;
use App\Http\Request;

/**
 * ScoreManager Class
 * Encapsulates the player's game state (Score and Streak).
 * Handles state manipulation via injected Session and Request objects.
 */
class ScoreManager
{
    private $session;
    private $request;

    public function __construct(Session $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;

        // Initialize if not set
        if (!$this->session->has('score')) {
            $this->session->set('score', 0);
        }
        if (!$this->session->has('streak')) {
            $this->session->set('streak', 0);
        }
    }

    public function getScore()
    {
        return $this->session->get('score', 0);
    }

    public function getStreak()
    {
        return $this->session->get('streak', 0);
    }

    public function recordSuccess()
    {
        $streak = $this->session->get('streak', 0) + 1;
        $this->session->set('streak', $streak);

        // Base points + Streak bonus
        $points = 10 + ($streak * 2);
        $score = $this->session->get('score', 0) + $points;
        $this->session->set('score', $score);

        // Check High Score
        $this->updateHighScore();

        return $points;
    }

    public function recordFailure()
    {
        $this->session->set('streak', 0);
    }

    public function resetGame()
    {
        $this->session->set('score', 0);
        $this->session->set('streak', 0);
    }

    public function getRank()
    {
        $score = $this->getScore();

        if ($score >= 1000)
            return '🦖 Kaiju';
        if ($score >= 500)
            return '👾 Monster';
        if ($score >= 250)
            return '🦍 Beast';
        if ($score >= 100)
            return '🦁 Predator';
        if ($score >= 50)
            return '🦊 Hunter';
        if ($score >= 10)
            return '🐣 Hatchling';

        return '🥚 Egg';
    }

    public function getHighScore()
    {
        return $this->request->getCookie('highscore', 0);
    }

    private function updateHighScore()
    {
        $current = $this->getScore();
        $high = $this->getHighScore();

        if ($current > $high) {
            // Set cookie for 1 year
            $this->request->setCookie('highscore', $current, time() + (86400 * 365), "/");
        }
    }
}
