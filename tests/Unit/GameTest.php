<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\BodyPart;
use App\Domain\Creature\Dog;
use App\Domain\Creature\Spider;
use App\Domain\Game;
use App\Tests\Fakes\DeterministicRandomizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testThrowsWhenConstructedWithNoCreatures(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Game([]);
    }

    public function testGeneratesDeterministicQuestionContainingCorrectAnswerAndUniqueChoices(): void
    {
        $spider = new Spider();
        $dog = new Dog();
        // Pick creature index 0 (Spider), part index 0 (Legs = 8), template index 0
        $game = new Game([$spider, $dog], new DeterministicRandomizer([0, 0, 0]));

        $question = $game->generateQuestion();

        self::assertSame($spider->getPartCount(BodyPart::Legs), $question->answer);
        self::assertSame('How many legs does a spider have?', $question->text);
        self::assertCount(4, $question->choices);
        self::assertCount(4, \array_unique($question->choices));
        self::assertContains($question->answer, $question->choices);
    }
}
