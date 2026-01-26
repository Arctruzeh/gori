<?php

namespace App;

class Human extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(2, 2, 2, 0, 0, 1, 0, 46, 32, 5, 7);
    }

    public function getName()
    {
        return 'human';
    }
}
