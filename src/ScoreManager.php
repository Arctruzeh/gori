<?php

namespace App;

/**
 * ScoreManager Class
 * Encapsulates the player's game state (Score and Streak).
 * Handles all session manipulation internally.
 */
class ScoreManager
{
    public function __construct()
    {
        // Ensure session is started (though usually handled by index.php, it's good safety)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize if not set
        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }
        if (!isset($_SESSION['streak'])) {
            $_SESSION['streak'] = 0;
        }
    }

    public function getScore()
    {
        return $_SESSION['score'];
    }

    public function getStreak()
    {
        return $_SESSION['streak'];
    }

    public function recordSuccess()
    {
        $_SESSION['streak']++;

        // Base points + Streak bonus
        $points = 10 + ($_SESSION['streak'] * 2);
        $_SESSION['score'] += $points;

        // Check High Score
        $this->updateHighScore();

        return $points;
    }

    public function recordFailure()
    {
        $_SESSION['streak'] = 0;
    }

    public function resetGame()
    {
        $_SESSION['score'] = 0;
        $_SESSION['streak'] = 0;
    }

    public function getRank()
    {
        $score = $_SESSION['score'];

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
        return $_COOKIE['highscore'] ?? 0;
    }

    private function updateHighScore()
    {
        $current = $_SESSION['score'];
        $high = $this->getHighScore();

        if ($current > $high) {
            // Set cookie for 1 year
            setcookie('highscore', $current, time() + (86400 * 365), "/");
        }
    }
}
