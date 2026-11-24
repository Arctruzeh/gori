<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Domain\Creature\Dog;
use App\Domain\Creature\Spider;
use App\Domain\Game;
use App\Http\Request;
use App\Kernel;
use App\Tests\Fakes\ArraySession;
use App\Tests\Fakes\DeterministicRandomizer;
use App\Tests\Fakes\InMemoryScoreRepository;
use App\Tests\Fakes\InMemoryUserRepository;
use App\View\Renderer;
use PHPUnit\Framework\TestCase;

final class KernelFlowTest extends TestCase
{
    private ArraySession $session;
    private InMemoryUserRepository $users;
    private InMemoryScoreRepository $scores;
    private Renderer $renderer;
    private DeterministicRandomizer $randomizer;
    private int $tokenSeq = 0;

    protected function setUp(): void
    {
        $this->session = new ArraySession();
        $this->users = new InMemoryUserRepository();
        $this->scores = new InMemoryScoreRepository($this->users);
        $this->renderer = new Renderer(__DIR__ . '/../../views');
        $this->randomizer = new DeterministicRandomizer([0]);
        $this->tokenSeq = 0;
    }

    private function dispatch(Request $request): \App\Http\Response
    {
        $kernel = Kernel::create(
            request: $request,
            session: $this->session,
            userModel: $this->users,
            scoreModel: $this->scores,
            renderer: $this->renderer,
            game: new Game([new Spider(), new Dog()], $this->randomizer),
            randomizer: $this->randomizer,
            tokenGenerator: function (): string {
                $this->tokenSeq++;
                return 'tok-' . $this->tokenSeq;
            },
        );

        return $kernel->handle();
    }

    public function testGameLoopHandlesCorrectDuplicateEmptyWrongAndReset(): void
    {
        // 1. Initial GET renders Spider legs question (8) with tok-1
        $get = $this->dispatch(new Request());
        self::assertSame(200, $get->statusCode());
        self::assertStringContainsString('How many legs does a spider have?', $get->content());
        self::assertStringContainsString('name="token" value="tok-1"', $get->content());

        // 2. Submit correct answer (8) with tok-1 -> success feedback + score=12, streak=1
        $correct = $this->dispatch(new Request(
            post: ['useranswer' => '8', 'token' => 'tok-1'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertSame(200, $correct->statusCode());
        self::assertStringContainsString('alert-success', $correct->content());
        self::assertSame(12, $this->session->get('score'));
        self::assertSame(1, $this->session->get('streak'));

        // 3. Re-submitting consumed tok-1 shows warning and does not change score
        $replay = $this->dispatch(new Request(
            post: ['useranswer' => '8', 'token' => 'tok-1'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertStringContainsString('This question has already been answered or is invalid.', $replay->content());
        self::assertSame(12, $this->session->get('score'));

        // 4. Empty answer shows warning
        $empty = $this->dispatch(new Request(
            post: ['useranswer' => '', 'token' => 'tok-3'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertStringContainsString('Put something in...', $empty->content());

        // 5. Wrong answer shows correct answer and resets streak
        $wrong = $this->dispatch(new Request(
            post: ['useranswer' => '999', 'token' => 'tok-4'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertStringContainsString('alert-danger', $wrong->content());
        self::assertStringContainsString('The correct answer was <strong>8</strong>.', $wrong->content());
        self::assertSame(0, $this->session->get('streak'));
        self::assertSame(12, $this->session->get('score'));

        // 6. Reset redirects to ./ and clears score
        $reset = $this->dispatch(new Request(
            post: ['action' => 'reset'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertSame(302, $reset->statusCode());
        self::assertSame('./', $reset->header('Location'));
        self::assertSame(0, $this->session->get('score'));
    }

    public function testRegisterLoginPlayProfileLeaderboardAndLogoutFlow(): void
    {
        // 1. Register user
        $regOk = $this->dispatch(new Request(
            query: ['action' => 'do_register'],
            post: ['username' => '  alice  ', 'password' => 'secret123'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertSame(302, $regOk->statusCode());
        self::assertSame('/?action=login', $regOk->header('Location'));

        // 2. Duplicate registration rejected
        $regDup = $this->dispatch(new Request(
            query: ['action' => 'do_register'],
            post: ['username' => 'alice', 'password' => 'other123'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertSame('/?action=register', $regDup->header('Location'));
        self::assertSame('Username already taken', $this->session->getFlash('error'));

        // 3. Login regenerates session ID and authenticates
        $this->session->set('score', 999);
        $login = $this->dispatch(new Request(
            query: ['action' => 'do_login'],
            post: ['username' => 'alice', 'password' => 'secret123'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));
        self::assertSame('/', $login->header('Location'));
        self::assertSame(1, $this->session->regenerateCount);
        self::assertSame('alice', $this->session->get('username'));
        self::assertFalse($this->session->has('score'));

        // 4. Play one winning round as logged-in user so score persists
        $this->dispatch(new Request());
        $this->dispatch(new Request(
            post: ['useranswer' => '8', 'token' => 'tok-1'],
            server: ['REQUEST_METHOD' => 'POST'],
        ));

        // 5. Profile displays username, best score (12), and rank (Hatchling)
        $profile = $this->dispatch(new Request(query: ['action' => 'profile']));
        self::assertSame(200, $profile->statusCode());
        self::assertStringContainsString('alice', $profile->content());
        self::assertStringContainsString('Hatchling', $profile->content());
        self::assertStringContainsString('12', $profile->content());

        // 6. Leaderboard displays alice's persisted score
        $leaderboard = $this->dispatch(new Request(query: ['action' => 'leaderboard']));
        self::assertSame(200, $leaderboard->statusCode());
        self::assertStringContainsString('alice', $leaderboard->content());
        self::assertStringContainsString('12', $leaderboard->content());

        // 7. Logout clears user session and redirects to login
        $logout = $this->dispatch(new Request(query: ['action' => 'logout']));
        self::assertSame('/?action=login', $logout->header('Location'));
        self::assertFalse($this->session->has('user_id'));

        // 8. Guest hitting profile redirects to login
        $guestProfile = $this->dispatch(new Request(query: ['action' => 'profile']));
        self::assertSame('/?action=login', $guestProfile->header('Location'));
    }
}
