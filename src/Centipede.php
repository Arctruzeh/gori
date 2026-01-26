<?php

namespace App;

class Centipede extends Creature
{
    public function __construct()
    {
        parent::__construct(100, 0, 2, 0, 1);
    }

    public function getName()
    {
        return 'centipede';
    }
}
