<?php

namespace App;

use App\Http\Request;
use App\Http\Session;
use App\Http\Response;
use App\Http\Controller\GameController;
use App\View\Renderer;

class Kernel
{
    private $session;
    private $request;
    private $renderer;
    private $scoreManager;

    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();
        $this->renderer = new Renderer(__DIR__ . '/../views');
        $this->scoreManager = new ScoreManager($this->session, $this->request);
    }

    public function handle(): Response
    {
        $action = $this->request->getQuery('action', 'game');

        switch ($action) {
            case 'login':
                return (new \App\Http\Controller\AuthController($this->session, $this->request, $this->renderer))->showLogin();
            case 'do_login':
                return (new \App\Http\Controller\AuthController($this->session, $this->request, $this->renderer))->login();
            case 'register':
                return (new \App\Http\Controller\AuthController($this->session, $this->request, $this->renderer))->showRegister();
            case 'do_register':
                return (new \App\Http\Controller\AuthController($this->session, $this->request, $this->renderer))->register();
            case 'logout':
                return (new \App\Http\Controller\AuthController($this->session, $this->request, $this->renderer))->logout();
            case 'profile':
                return (new \App\Http\Controller\ProfileController($this->session, $this->renderer))->index();
            case 'leaderboard':
                return (new \App\Http\Controller\ProfileController($this->session, $this->renderer))->leaderboard();
            case 'game':
            default:
                return (new GameController(
                    $this->session,
                    $this->request,
                    $this->scoreManager,
                    $this->renderer
                ))->index();
        }
    }
}
