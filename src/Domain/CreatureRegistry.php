<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Creature\Bird;
use App\Domain\Creature\Capybara;
use App\Domain\Creature\Centipede;
use App\Domain\Creature\Chicken;
use App\Domain\Creature\Cow;
use App\Domain\Creature\Dog;
use App\Domain\Creature\Duck;
use App\Domain\Creature\Goat;
use App\Domain\Creature\Horse;
use App\Domain\Creature\Human;
use App\Domain\Creature\Pig;
use App\Domain\Creature\Platypus;
use App\Domain\Creature\Sheep;
use App\Domain\Creature\Sloth;
use App\Domain\Creature\Spider;

final class CreatureRegistry
{
    /**
     * @return Creature[]
     */
    public static function defaultCreatures(): array
    {
        return [
            new Dog(),
            new Human(),
            new Spider(),
            new Bird(),
            new Centipede(),
            new Cow(),
            new Pig(),
            new Sheep(),
            new Chicken(),
            new Horse(),
            new Duck(),
            new Goat(),
            new Capybara(),
            new Sloth(),
            new Platypus(),
        ];
    }
}
