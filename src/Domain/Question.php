<?php

declare(strict_types=1);

namespace App\Domain;

final class Question
{
    /**
     * @param int[] $choices
     */
    public function __construct(
        public readonly string $text,
        public readonly int $answer,
        public readonly array $choices,
    ) {
    }
}
