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
        // Simple routing: if we had more controllers, we would add routing logic here.
        $controller = new GameController(
            $this->session,
            $this->request,
            $this->scoreManager,
            $this->renderer
        );

        return $controller->index();
    }
}
