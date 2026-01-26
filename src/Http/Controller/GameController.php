<?php

namespace App\Http\Controller;

use App\Game;
use App\ScoreManager;
use App\Http\Request;
use App\Http\Session;
use App\Http\Response;
use App\View\Renderer;

class GameController
{
    private $session;
    private $request;
    private $scoreManager;
    private $renderer;

    public function __construct(Session $session, Request $request, ScoreManager $scoreManager, Renderer $renderer)
    {
        $this->session = $session;
        $this->request = $request;
        $this->scoreManager = $scoreManager;
        $this->renderer = $renderer;
    }

    public function index(): Response
    {
        $message = '';
        $messageType = '';

        // Handle Reset Request
        if ($this->request->getPost('action') === 'reset') {
            $this->scoreManager->resetGame();
            return new Response('', 302, ['Location' => './']);
        }

        // Initialize Pending Answers in Session if not exists
        if (!$this->session->has('pending_answers')) {
            $this->session->set('pending_answers', []);
        }

        if ($this->request->isPost() && $this->request->getPost('useranswer') !== null) {
            $useranswer = $this->request->getPost('useranswer');
            $token = $this->request->getPost('token', '');

            $pendingAnswers = $this->session->get('pending_answers');

            if (!isset($pendingAnswers[$token])) {
                $message = '<div class="alert alert-warning">This question has already been answered or is invalid.</div>';
                $messageType = 'error';
            } else {
                $correctAnswer = $pendingAnswers[$token];

                // Remove token immediately
                unset($pendingAnswers[$token]);
                $this->session->set('pending_answers', $pendingAnswers);

                if ($useranswer === '') {
                    $message = '<div class="alert alert-warning">Put something in...</div>';
                    $messageType = 'empty';
                } elseif ((string) $useranswer === (string) $correctAnswer) {
                    $message = $this->generateSuccessMessage();
                    $messageType = 'success';
                    $this->scoreManager->recordSuccess();
                } else {
                    $message = $this->generateFailureMessage($correctAnswer);
                    $messageType = 'error';
                    $this->scoreManager->recordFailure();
                }
            }
        }

        $game = new Game();
        $roundData = $game->generateQuestion();

        $token = \uniqid();
        $pendingAnswers = $this->session->get('pending_answers', []);
        $pendingAnswers[$token] = $roundData['answer'];

        if (\count($pendingAnswers) > 20) {
            $pendingAnswers = \array_slice($pendingAnswers, -20, 20, true);
        }
        $this->session->set('pending_answers', $pendingAnswers);

        $viewData = [
            'score' => $this->scoreManager->getScore(),
            'bestScore' => $this->scoreManager->getHighScore(),
            'rank' => $this->scoreManager->getRank(),
            'streak' => $this->scoreManager->getStreak(),
            'message' => $message,
            'messageType' => $messageType,
            'question' => $roundData['question'],
            'choices' => $roundData['choices'],
            'token' => $token,
            'isLoggedIn' => $this->session->has('user_id'),
            'username' => $this->session->get('username')
        ];

        $content = $this->renderer->render('game', $viewData);
        return new Response($content);
    }

    private function generateSuccessMessage(): string
    {
        $geniusMessages = [
            "You are a genius!",
            "You are a total genius!",
            "Absolute genius!",
            "Look at you, what a genius.",
            "Clearly, you are a genius.",
            "You are a genius, obviously.",
            "Pure genius!",
            "A true genius indeed!",
            "Behold, a genius!"
        ];
        $selectedGenius = $geniusMessages[\array_rand($geniusMessages)];
        return "<div class=\"alert alert-success\">$selectedGenius</div>";
    }

    private function generateFailureMessage($correctAnswer): string
    {
        $idiotMessages = [
            "You are an idiot!",
            "You are such an idiot!",
            "Absolute idiot!",
            "Total idiot move.",
            "You are an idiot, clearly.",
            "Don't be such an idiot!",
            "What an idiot!",
            "You complete idiot!",
            "Maximum idiot level reached."
        ];
        $selectedIdiot = $idiotMessages[\array_rand($idiotMessages)];
        return "<div class=\"alert alert-danger\">$selectedIdiot The correct answer was <strong>$correctAnswer</strong>.</div>";
    }
}
