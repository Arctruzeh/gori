<?php

declare(strict_types=1);

namespace App\Domain;

final class Feedback
{
    public function __construct(
        public readonly string $variant,
        public readonly string $text,
        public readonly ?int $correctAnswer = null,
    ) {
    }

    public static function success(string $text): self
    {
        return new self('success', $text);
    }

    public static function danger(string $text, int $correctAnswer): self
    {
        return new self('danger', $text, $correctAnswer);
    }

    public static function warning(string $text): self
    {
        return new self('warning', $text);
    }
}
