<?php

namespace App;

/**
 * Game Logic Class
 * Handles the flow of the game, keeping the global scope clean.
 */
class Game
{
    private $creatures = [];
    private $parts = ['legs', 'arms', 'eyes', 'wings', 'tails'];
    private $possibleAnswers = [];

    public function __construct()
    {
        // We put our objects into a list.
        $this->creatures = [
            new Dog(),
            new Human(),
            new Spider(),
            new Bird(),
            new Centipede()
        ];

        // Pre-calculate all possible valid answers
        foreach ($this->creatures as $creature) {
            foreach ($this->parts as $part) {
                $count = $creature->getPartCount($part);
                if (!in_array($count, $this->possibleAnswers)) {
                    $this->possibleAnswers[] = $count;
                }
            }
        }
        sort($this->possibleAnswers);
    }

    public function generateQuestion()
    {
        // Pick random creature object
        $randomCreature = $this->creatures[array_rand($this->creatures)];

        // Pick random part name
        $randomPart = $this->parts[array_rand($this->parts)];

        // Get the answer directly from the object (Polymorphism)
        $trueAnswer = $randomCreature->getPartCount($randomPart);

        // Generate choices (1 correct + 3 wrong)
        $choices = [$trueAnswer];

        // Get distractor answers from our pool of valid game numbers
        $pool = $this->possibleAnswers;
        shuffle($pool);

        foreach ($pool as $option) {
            if (count($choices) >= 4) {
                break;
            }
            if ($option !== $trueAnswer) {
                $choices[] = $option;
            }
        }

        // Shuffle the final buttons so the answer isn't always first
        shuffle($choices);

        return [
            'question' => "How many $randomPart does a " . $randomCreature->getName() . " have? ",
            'answer' => $trueAnswer,
            'choices' => $choices
        ];
    }
}
