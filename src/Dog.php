<?php

namespace App;

class Dog extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(4, 0, 2, 0, 1, 1, 0, 78, 42, 5, 7);
    }

    public function getName()
    {
        return 'dog';
    }
}
