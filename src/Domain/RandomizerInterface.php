<?php

declare(strict_types=1);

namespace App\Domain;

interface RandomizerInterface
{
    public function pickIndex(int $count): int;

    /**
     * @template T
     * @param list<T> $items
     * @return list<T>
     */
    public function shuffle(array $items): array;
}
