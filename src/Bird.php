<?php

namespace App;

class Bird extends Creature
{
    public function __construct()
    {
        parent::__construct(2, 0, 2, 2, 1);
    }

    public function getName()
    {
        return 'bird';
    }
}
