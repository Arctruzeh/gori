<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Feedback;
use App\Domain\Game;
use App\Domain\ScoreManager;
use App\Http\Request;
use App\Http\Response;
use App\Http\Session;
use App\View\Renderer;

final class GameController
{
    private const GENIUS_MESSAGES = [
        'You are a genius!',
        'You are a total genius!',
        'Absolute genius!',
        'Look at you, what a genius.',
        'Clearly, you are a genius.',
        'You are a genius, obviously.',
        'Pure genius!',
        'A true genius indeed!',
        'Behold, a genius!',
    ];

    private const IDIOT_MESSAGES = [
        'You are an idiot!',
        'You are such an idiot!',
        'Absolute idiot!',
        'Total idiot move.',
        'You are an idiot, clearly.',
        "Don't be such an idiot!",
        'What an idiot!',
        'You complete idiot!',
        'Maximum idiot level reached.',
    ];

    public function __construct(
        private readonly Session $session,
        private readonly Request $request,
        private readonly Game $game,
        private readonly ScoreManager $scoreManager,
        private readonly Renderer $renderer,
    ) {
    }

    public function index(): Response
    {
        if ($this->request->getPost('action') === 'reset') {
            $this->scoreManager->resetGame();
            return Response::redirect('./');
        }

        if (!$this->session->has('pending_answers')) {
            $this->session->set('pending_answers', []);
        }

        $feedback = null;

        if ($this->request->isPost() && $this->request->getPost('useranswer') !== null) {
            $userAnswer = (string) $this->request->getPost('useranswer');
            $token = (string) $this->request->getPost('token', '');
            $feedback = $this->evaluateSubmission($userAnswer, $token);
        }

        $question = $this->game->generateQuestion();

        $token = \uniqid('', true);
        /** @var array<string, int> $pendingAnswers */
        $pendingAnswers = (array) $this->session->get('pending_answers', []);
        $pendingAnswers[$token] = $question->answer;

        if (\count($pendingAnswers) > 20) {
            $pendingAnswers = \array_slice($pendingAnswers, -20, 20, true);
        }
        $this->session->set('pending_answers', $pendingAnswers);

        $viewData = [
            'score' => $this->scoreManager->getScore(),
            'bestScore' => $this->scoreManager->getHighScore(),
            'rank' => $this->scoreManager->getRank(),
            'streak' => $this->scoreManager->getStreak(),
            'feedback' => $feedback,
            'question' => $question->text,
            'choices' => $question->choices,
            'token' => $token,
            'isLoggedIn' => $this->session->has('user_id'),
            'username' => $this->session->get('username'),
            'currentAction' => 'game',
            'pageTitle' => 'Genius or Idiot',
            'showScoreboard' => true,
            'layoutSize' => 'default',
        ];

        return new Response($this->renderer->renderWithLayout('game', $viewData));
    }

    private function evaluateSubmission(string $userAnswer, string $token): Feedback
    {
        /** @var array<string, int> $pendingAnswers */
        $pendingAnswers = (array) $this->session->get('pending_answers', []);

        if (!isset($pendingAnswers[$token])) {
            return Feedback::warning('This question has already been answered or is invalid.');
        }

        $correctAnswer = (int) $pendingAnswers[$token];
        unset($pendingAnswers[$token]);
        $this->session->set('pending_answers', $pendingAnswers);

        if ($userAnswer === '') {
            return Feedback::warning('Put something in...');
        }

        if ($userAnswer === (string) $correctAnswer) {
            $this->scoreManager->recordSuccess();
            return Feedback::success(self::GENIUS_MESSAGES[\array_rand(self::GENIUS_MESSAGES)]);
        }

        $this->scoreManager->recordFailure();
        return Feedback::danger(
            self::IDIOT_MESSAGES[\array_rand(self::IDIOT_MESSAGES)],
            $correctAnswer
        );
    }
}
