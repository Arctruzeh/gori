<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Http\SessionInterface;
use App\Model\UserRepositoryInterface;
use App\View\Renderer;

final class AuthController
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly Request $request,
        private readonly Renderer $renderer,
        private readonly UserRepositoryInterface $userModel,
    ) {
    }

    public function showLogin(): Response
    {
        return new Response($this->renderer->renderWithLayout('auth/login', [
            'error' => $this->session->getFlash('error'),
            'success' => $this->session->getFlash('success'),
            'isLoggedIn' => $this->session->has('user_id'),
            'username' => $this->session->get('username'),
            'currentAction' => 'login',
            'pageTitle' => 'Login - Genius or Idiot',
            'layoutSize' => 'narrow',
        ]));
    }

    public function login(): Response
    {
        $username = \trim((string) $this->request->input('username', ''));
        $password = (string) $this->request->input('password', '');

        $user = $this->userModel->findByUsername($username);

        if ($user !== null && $this->userModel->verifyPassword($password, (string) $user['password_hash'])) {
            $this->clearGameSessionState();
            $this->session->regenerateId(true);
            $this->session->set('user_id', (int) $user['id']);
            $this->session->set('username', (string) $user['username']);
            return Response::redirect('/');
        }

        $this->session->setFlash('error', 'Invalid credentials');
        return Response::redirect('/?action=login');
    }

    public function showRegister(): Response
    {
        return new Response($this->renderer->renderWithLayout('auth/register', [
            'error' => $this->session->getFlash('error'),
            'isLoggedIn' => $this->session->has('user_id'),
            'username' => $this->session->get('username'),
            'currentAction' => 'register',
            'pageTitle' => 'Register - Genius or Idiot',
            'layoutSize' => 'narrow',
        ]));
    }

    public function register(): Response
    {
        $username = \trim((string) $this->request->input('username', ''));
        $password = (string) $this->request->input('password', '');

        if ($username === '' || $password === '') {
            $this->session->setFlash('error', 'Username and password required');
            return Response::redirect('/?action=register');
        }

        if ($this->userModel->create($username, $password)) {
            $this->session->setFlash('success', 'Registration successful! Please login.');
            return Response::redirect('/?action=login');
        }

        $this->session->setFlash('error', 'Username already taken');
        return Response::redirect('/?action=register');
    }

    public function logout(): Response
    {
        $this->session->remove('user_id');
        $this->session->remove('username');
        $this->clearGameSessionState();
        return Response::redirect('/?action=login');
    }

    private function clearGameSessionState(): void
    {
        $this->session->remove('score');
        $this->session->remove('streak');
        $this->session->remove('highscore');
        $this->session->remove('current_run_score_id');
        $this->session->remove('pending_answers');
    }
}
