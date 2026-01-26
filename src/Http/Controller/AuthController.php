<?php

namespace App\Http\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Http\Session;
use App\Model\User;
use App\View\Renderer;

class AuthController
{
    private $session;
    private $request;
    private $renderer;
    private $userModel;

    public function __construct(Session $session, Request $request, Renderer $renderer)
    {
        $this->session = $session;
        $this->request = $request;
        $this->renderer = $renderer;
        $this->userModel = new User();
    }

    public function showLogin()
    {
        return new Response($this->renderer->render('auth/login', ['error' => $this->session->getFlash('error')]));
    }

    public function login()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        $user = $this->userModel->findByUsername($username);

        if ($user && $this->userModel->verifyPassword($password, $user['password_hash'])) {
            $this->session->set('user_id', $user['id']);
            $this->session->set('username', $user['username']);
            return new Response('', 302, ['Location' => '/']);
        }

        $this->session->setFlash('error', 'Invalid credentials');
        return new Response('', 302, ['Location' => '/?action=login']);
    }

    public function showRegister()
    {
        return new Response($this->renderer->render('auth/register', ['error' => $this->session->getFlash('error')]));
    }

    public function register()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        if (empty($username) || empty($password)) {
            $this->session->setFlash('error', 'Username and password required');
            return new Response('', 302, ['Location' => '/?action=register']);
        }

        if ($this->userModel->create($username, $password)) {
            $this->session->setFlash('success', 'Registration successful! Please login.');
            return new Response('', 302, ['Location' => '/?action=login']);
        }

        $this->session->setFlash('error', 'Username already taken');
        return new Response('', 302, ['Location' => '/?action=register']);
    }

    public function logout()
    {
        $this->session->remove('user_id');
        $this->session->remove('username');
        $this->session->remove('score');
        $this->session->remove('streak');
        return new Response('', 302, ['Location' => '/?action=login']);
    }
}
