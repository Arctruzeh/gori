<?php

namespace App;

class Spider extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        parent::__construct(8, 0, 8, 0, 0, 1, 0, 24, 0, 0, 0);
    }

    public function getName()
    {
        return 'spider';
    }
}
