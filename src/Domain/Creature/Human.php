<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Human extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 2,
            arms: 2,
            eyes: 2,
            wings: 0,
            tails: 0,
            stomachs: 1,
            antennae: 0,
            chromosomes: 46,
            teeth: 32,
            toes: 5,
            neckVertebrae: 7,
        );
    }

    public function getName(): string
    {
        return 'human';
    }
}
