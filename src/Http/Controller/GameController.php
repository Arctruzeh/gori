<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Feedback;
use App\Domain\Game;
use App\Domain\NativeRandomizer;
use App\Domain\RandomizerInterface;
use App\Domain\ScoreManager;
use App\Http\Request;
use App\Http\Response;
use App\Http\SessionInterface;
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

    private readonly RandomizerInterface $randomizer;

    /** @var callable(): string */
    private $tokenGenerator;

    /**
     * @param (callable(): string)|null $tokenGenerator
     */
    public function __construct(
        private readonly SessionInterface $session,
        private readonly Request $request,
        private readonly Game $game,
        private readonly ScoreManager $scoreManager,
        private readonly Renderer $renderer,
        ?RandomizerInterface $randomizer = null,
        ?callable $tokenGenerator = null,
    ) {
        $this->randomizer = $randomizer ?? new NativeRandomizer();
        $this->tokenGenerator = $tokenGenerator ?? static fn (): string => \bin2hex(\random_bytes(16));
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

        $token = ($this->tokenGenerator)();
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
            $message = self::GENIUS_MESSAGES[$this->randomizer->pickIndex(\count(self::GENIUS_MESSAGES))];
            return Feedback::success($message);
        }

        $this->scoreManager->recordFailure();
        $message = self::IDIOT_MESSAGES[$this->randomizer->pickIndex(\count(self::IDIOT_MESSAGES))];
        return Feedback::danger($message, $correctAnswer);
    }
}
