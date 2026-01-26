<?php

namespace App;

class Dog extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails
        parent::__construct(4, 0, 2, 0, 1);
    }

    public function getName()
    {
        return 'dog';
    }
}
