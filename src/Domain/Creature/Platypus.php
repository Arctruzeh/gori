<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Platypus extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 4,
            arms: 0,
            eyes: 2,
            wings: 0,
            tails: 1,
            stomachs: 0,
            antennae: 0,
            chromosomes: 52,
            teeth: 0,
            toes: 5,
            neckVertebrae: 7,
        );
    }

    public function getName(): string
    {
        return 'platypus';
    }
}
