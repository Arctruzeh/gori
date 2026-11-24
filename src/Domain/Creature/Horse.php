<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Horse extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 4,
            arms: 0,
            eyes: 2,
            wings: 0,
            tails: 1,
            stomachs: 1,
            antennae: 0,
            chromosomes: 64,
            teeth: 40,
            toes: 1,
            neckVertebrae: 7,
        );
    }

    public function getName(): string
    {
        return 'horse';
    }
}
