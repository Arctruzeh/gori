<?php

declare(strict_types=1);

namespace App\Http;

use RuntimeException;

final class Router
{
    /** @var array<string, callable(): Response> */
    private array $routes = [];

    /** @var (callable(): Response)|null */
    private $defaultHandler = null;

    /**
     * @param callable(): Response $handler
     */
    public function register(string $action, callable $handler): void
    {
        $this->routes[$action] = $handler;
    }

    /**
     * @param callable(): Response $handler
     */
    public function setDefault(callable $handler): void
    {
        $this->defaultHandler = $handler;
    }

    public function dispatch(string $action): Response
    {
        if (isset($this->routes[$action])) {
            return ($this->routes[$action])();
        }

        if ($this->defaultHandler !== null) {
            return ($this->defaultHandler)();
        }

        throw new RuntimeException("No route registered for action '{$action}'.");
    }
}
