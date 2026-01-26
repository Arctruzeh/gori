<?php

namespace App;

class Goat extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        // Goats have a single stomach with four compartments
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 60, 32, 2, 7);
    }

    public function getName()
    {
        return 'goat';
    }
}
