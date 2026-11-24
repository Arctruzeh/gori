<?php

declare(strict_types=1);

namespace App\Domain\Creature;

use App\Domain\Creature;

final class Spider extends Creature
{
    public function __construct()
    {
        parent::__construct(
            legs: 8,
            arms: 0,
            eyes: 8,
            wings: 0,
            tails: 0,
            stomachs: 1,
            antennae: 0,
            chromosomes: 24,
            teeth: 0,
            toes: 0,
            neckVertebrae: 0,
        );
    }

    public function getName(): string
    {
        return 'spider';
    }
}
