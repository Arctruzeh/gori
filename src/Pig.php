<?php

namespace App;

class Pig extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 38, 44, 4, 7);
    }

    public function getName()
    {
        return 'pig';
    }
}
