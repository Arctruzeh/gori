<?php

namespace App;

class Duck extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(2, 0, 2, 2, 1, 1, 0, 80, 0, 4, 16);
    }

    public function getName()
    {
        return 'duck';
    }
}
