<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\BodyPart;
use App\Domain\CreatureRegistry;
use App\Domain\Rank;
use PHPUnit\Framework\TestCase;

final class CreatureAndRankTest extends TestCase
{
    public function testAllDefaultCreaturesHaveNamesAndValidPartCounts(): void
    {
        $creatures = CreatureRegistry::defaultCreatures();
        self::assertCount(15, $creatures);

        $parts = BodyPart::cases();
        self::assertCount(11, $parts);

        foreach ($creatures as $creature) {
            self::assertNotSame('', $creature->getName());
            foreach ($parts as $part) {
                self::assertGreaterThanOrEqual(0, $creature->getPartCount($part));
                self::assertNotSame('', $part->label());
            }
        }
    }

    public function testRankThresholds(): void
    {
        self::assertSame('🥚 Egg', Rank::forScore(0));
        self::assertSame('🥚 Egg', Rank::forScore(9));
        self::assertSame('🐣 Hatchling', Rank::forScore(10));
        self::assertSame('🦊 Hunter', Rank::forScore(50));
        self::assertSame('🦁 Predator', Rank::forScore(100));
        self::assertSame('🦍 Beast', Rank::forScore(250));
        self::assertSame('👾 Monster', Rank::forScore(500));
        self::assertSame('🦖 Kaiju', Rank::forScore(1000));
    }
}
