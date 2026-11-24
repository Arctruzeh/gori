<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Centipede extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 100,
            arms: 0,
            eyes: 2,
            wings: 0,
            tails: 1,
            stomachs: 1,
            antennae: 2,
            chromosomes: 30,
            teeth: 0,
            toes: 0,
            neckVertebrae: 0,
        );
    }

    public function getName(): string
    {
        return 'centipede';
    }
}
