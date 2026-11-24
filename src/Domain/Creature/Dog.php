<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Dog extends Creature
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
            chromosomes: 78,
            teeth: 42,
            toes: 5,
            neckVertebrae: 7,
        );
    }

    public function getName(): string
    {
        return 'dog';
    }
}
