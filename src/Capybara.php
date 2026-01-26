<?php

namespace App;

class Capybara extends Creature
{
    public function __construct()
    {
        // legs, arms, eyes, wings, tails, stomachs, antennae
        // Capybaras don't have visible tails
        parent::__construct(4, 0, 2, 0, 0, 1, 0, 66, 20, 4, 7);
    }

    public function getName()
    {
        return 'capybara';
    }
}
