<?php

namespace App;

class Sloth extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        // Sloths have a small tail and a single stomach with four compartments
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 52, 20, 3, 9);
    }

    public function getName()
    {
        return 'sloth';
    }
}
