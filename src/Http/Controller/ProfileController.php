<?php

namespace App\Http\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Http\Session;
use App\Model\Score;
use App\Model\User;
use App\View\Renderer;

class ProfileController
{
    private $session;
    private $renderer;
    private $scoreModel;
    private $userModel;

    public function __construct(Session $session, Renderer $renderer)
    {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->scoreModel = new Score();
        $this->userModel = new User();
    }

    public function index()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return new Response('', 302, ['Location' => '/?action=login']);
        }

        $user = $this->userModel->findById($userId);
        $recentScores = $this->scoreModel->getUserHighScores($userId);
        $bestScore = $this->scoreModel->getUserBestScore($userId);
        $rank = $this->getRank($bestScore);

        return new Response($this->renderer->render('profile', [
            'user' => $user,
            'recentScores' => $recentScores,
            'bestScore' => $bestScore,
            'rank' => $rank
        ]));
    }

    public function leaderboard()
    {
        $highScores = $this->scoreModel->getHighScores(20);
        return new Response($this->renderer->render('leaderboard', ['highScores' => $highScores]));
    }

    private function getRank($score)
    {
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
}
