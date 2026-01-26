<?php

namespace App;

class Spider extends Creature
{
    public function __construct()
    {
        parent::__construct(8, 0, 8, 0, 0);
    }

    public function getName()
    {
        return 'spider';
    }
}
