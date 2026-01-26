<?php

namespace App;

class Sheep extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        // Sheep have a single stomach with four compartments
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 54, 32, 2, 7);
    }

    public function getName()
    {
        return 'sheep';
    }
}
