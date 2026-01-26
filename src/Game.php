<?php

namespace App;

/**
 * Game Logic Class
 * Handles the flow of the game, keeping the global scope clean.
 */
class Game
{
    private $creatures = [];
    private $parts = ['legs', 'arms', 'eyes', 'wings', 'tails', 'stomachs', 'antennae', 'chromosomes', 'teeth', 'toes', 'neck_vertebrae'];
    private $possibleAnswers = [];

    public function __construct()
    {
        // We put our objects into a list.
        $this->creatures = [
            new Dog(),
            new Human(),
            new Spider(),
            new Bird(),
            new Centipede(),
            new Cow(),
            new Pig(),
            new Sheep(),
            new Chicken(),
            new Horse(),
            new Duck(),
            new Goat(),
            new Capybara(),
            new Sloth(),
            new Platypus()
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

        $name = $randomCreature->getName();
        $displayName = str_replace('_', ' ', $randomPart);
        $templates = [
            "How many $displayName does a $name have?",
            "A $name has how many $displayName?",
            "Identify the number of $displayName on a $name.",
            "Quick! How many $displayName for a $name?",
            "The total count of $displayName on a $name is..."
        ];

        $questionText = $templates[array_rand($templates)];

        return [
            'question' => $questionText,
            'answer' => $trueAnswer,
            'choices' => $choices
        ];
    }
}
