<?php

namespace App;

class Horse extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 64, 40, 1, 7);
    }

    public function getName()
    {
        return 'horse';
    }
}
