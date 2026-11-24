<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Capybara extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 4,
            arms: 0,
            eyes: 2,
            wings: 0,
            tails: 0,
            stomachs: 1,
            antennae: 0,
            chromosomes: 66,
            teeth: 20,
            toes: 4,
            neckVertebrae: 7,
        );
    }

    public function getName(): string
    {
        return 'capybara';
    }
}
