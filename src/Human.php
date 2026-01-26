<?php

namespace App;

class Human extends Creature
{
    public function __construct()
    {
        parent::__construct(2, 2, 2, 0, 0);
    }

    public function getName()
    {
        return 'human';
    }
}
