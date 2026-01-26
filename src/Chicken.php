<?php

namespace App;

class Chicken extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(2, 0, 2, 2, 1, 1, 0, 78, 0, 4, 14);
    }

    public function getName()
    {
        return 'chicken';
    }
}
