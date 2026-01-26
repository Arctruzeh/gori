<?php

namespace App;

class Platypus extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(4, 0, 2, 0, 1, 0, 0, 52, 0, 5, 7);
    }

    public function getName()
    {
        return 'platypus';
    }
}
