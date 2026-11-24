<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Rank;
use App\Http\Response;
use App\Http\Session;
use App\Model\Score;
use App\Model\User;
use App\View\Renderer;

final class ProfileController
{
    public function __construct(
        private readonly Session $session,
        private readonly Renderer $renderer,
        private readonly User $userModel,
        private readonly Score $scoreModel,
    ) {
    }

    public function index(): Response
    {
        $userId = $this->session->get('user_id');
        if ($userId === null) {
            return Response::redirect('/?action=login');
        }

        $user = $this->userModel->findById((int) $userId);
        if ($user === null) {
            return Response::redirect('/?action=logout');
        }

        $recentScores = $this->scoreModel->getUserRecentScores((int) $userId);
        $bestScore = $this->scoreModel->getUserBestScore((int) $userId);
        $rank = Rank::forScore($bestScore);

        return new Response($this->renderer->renderWithLayout('profile', [
            'user' => $user,
            'recentScores' => $recentScores,
            'bestScore' => $bestScore,
            'rank' => $rank,
            'isLoggedIn' => true,
            'username' => (string) $user['username'],
            'currentAction' => 'profile',
            'pageTitle' => 'Profile - Genius or Idiot',
            'layoutSize' => 'wide',
        ]));
    }

    public function leaderboard(): Response
    {
        $highScores = $this->scoreModel->getHighScores(20);
        return new Response($this->renderer->renderWithLayout('leaderboard', [
            'highScores' => $highScores,
            'isLoggedIn' => $this->session->has('user_id'),
            'username' => $this->session->get('username'),
            'currentAction' => 'leaderboard',
            'pageTitle' => 'Leaderboard - Genius or Idiot',
            'layoutSize' => 'wide',
        ]));
    }
}
