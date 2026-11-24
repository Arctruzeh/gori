<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Bird extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 2,
            arms: 0,
            eyes: 2,
            wings: 2,
            tails: 1,
            stomachs: 1,
            antennae: 0,
            chromosomes: 80,
            teeth: 0,
            toes: 4,
            neckVertebrae: 14,
        );
    }

    public function getName(): string
    {
        return 'bird';
    }
}
