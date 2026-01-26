<?php

namespace App;

class Centipede extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(100, 0, 2, 0, 1, 1, 2, 30, 0, 0, 0);
    }

    public function getName()
    {
        return 'centipede';
    }
}
