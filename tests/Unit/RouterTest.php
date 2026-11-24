<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Http\Response;
use App\Http\Router;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class RouterTest extends TestCase
{
    public function testDispatchesRegisteredRouteAndFallsBackToDefault(): void
    {
        $router = new Router();
        $router->register('profile', static fn (): Response => new Response('profile-page'));
        $router->setDefault(static fn (): Response => new Response('default-game'));

        self::assertSame('profile-page', $router->dispatch('profile')->content());
        self::assertSame('default-game', $router->dispatch('unknown')->content());
    }

    public function testThrowsWhenNoRouteMatchesAndNoDefaultIsSet(): void
    {
        $router = new Router();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("No route registered for action 'missing'.");

        $router->dispatch('missing');
    }
}
