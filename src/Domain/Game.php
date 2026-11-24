<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;

final class Game
{
    /** @var Creature[] */
    private readonly array $creatures;

    /** @var BodyPart[] */
    private readonly array $parts;

    /** @var int[] */
    private array $possibleAnswers = [];

    /**
     * @param Creature[] $creatures
     */
    public function __construct(array $creatures)
    {
        if ($creatures === []) {
            throw new InvalidArgumentException('Game requires at least one creature.');
        }

        $this->creatures = \array_values($creatures);
        $this->parts = BodyPart::cases();

        foreach ($this->creatures as $creature) {
            foreach ($this->parts as $part) {
                $count = $creature->getPartCount($part);
                if (!\in_array($count, $this->possibleAnswers, true)) {
                    $this->possibleAnswers[] = $count;
                }
            }
        }

        \sort($this->possibleAnswers);
    }

    public function generateQuestion(): Question
    {
        $randomCreature = $this->creatures[\array_rand($this->creatures)];
        $randomPart = $this->parts[\array_rand($this->parts)];

        $trueAnswer = $randomCreature->getPartCount($randomPart);
        $choices = [$trueAnswer];

        $pool = $this->possibleAnswers;
        \shuffle($pool);

        foreach ($pool as $option) {
            if (\count($choices) >= 4) {
                break;
            }
            if ($option !== $trueAnswer) {
                $choices[] = $option;
            }
        }

        \shuffle($choices);

        $name = $randomCreature->getName();
        $displayName = $randomPart->label();
        $templates = [
            "How many {$displayName} does a {$name} have?",
            "A {$name} has how many {$displayName}?",
            "Identify the number of {$displayName} on a {$name}.",
            "Quick! How many {$displayName} for a {$name}?",
            "The total count of {$displayName} on a {$name} is...",
        ];

        return new Question(
            text: $templates[\array_rand($templates)],
            answer: $trueAnswer,
            choices: $choices,
        );
    }
}
