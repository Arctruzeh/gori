<?php

declare(strict_types=1);

namespace App\Domain;

final class Rank
{
    private const TIERS = [
        1000 => '🦖 Kaiju',
        500 => '👾 Monster',
        250 => '🦍 Beast',
        100 => '🦁 Predator',
        50 => '🦊 Hunter',
        10 => '🐣 Hatchling',
    ];

    public static function forScore(int $score): string
    {
        foreach (self::TIERS as $threshold => $label) {
            if ($score >= $threshold) {
                return $label;
            }
        }

        return '🥚 Egg';
    }
}
