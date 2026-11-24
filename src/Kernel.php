<?php

declare(strict_types=1);

namespace App;

use App\Config\DatabaseConfig;
use App\Database\Connection;
use App\Domain\CreatureRegistry;
use App\Domain\Game;
use App\Domain\ScoreManager;
use App\Http\Controller\AuthController;
use App\Http\Controller\GameController;
use App\Http\Controller\ProfileController;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;
use App\Http\Session;
use App\Model\Score;
use App\Model\User;
use App\View\Renderer;

final class Kernel
{
    public function __construct(
        private readonly Request $request,
        private readonly Router $router,
    ) {
    }

    public static function boot(): self
    {
        $session = new Session();
        $request = Request::fromGlobals();
        $renderer = new Renderer(__DIR__ . '/../views');

        $pdo = Connection::create(DatabaseConfig::fromEnvironment());
        $userModel = new User($pdo);
        $scoreModel = new Score($pdo);

        $game = new Game(CreatureRegistry::defaultCreatures());
        $scoreManager = new ScoreManager($session, $scoreModel);

        $gameController = new GameController($session, $request, $game, $scoreManager, $renderer);
        $authController = new AuthController($session, $request, $renderer, $userModel);
        $profileController = new ProfileController($session, $renderer, $userModel, $scoreModel);

        $router = new Router();
        $router->register('login', fn (): Response => $authController->showLogin());
        $router->register('do_login', fn (): Response => $authController->login());
        $router->register('register', fn (): Response => $authController->showRegister());
        $router->register('do_register', fn (): Response => $authController->register());
        $router->register('logout', fn (): Response => $authController->logout());
        $router->register('profile', fn (): Response => $profileController->index());
        $router->register('leaderboard', fn (): Response => $profileController->leaderboard());
        $router->register('game', fn (): Response => $gameController->index());
        $router->setDefault(fn (): Response => $gameController->index());

        return new self($request, $router);
    }

    public function handle(): Response
    {
        $action = (string) $this->request->getQuery('action', 'game');
        return $this->router->dispatch($action);
    }
}
