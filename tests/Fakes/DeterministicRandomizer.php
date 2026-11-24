<?php

declare(strict_types=1);

namespace App\Tests\Fakes;

use App\Domain\RandomizerInterface;

final class DeterministicRandomizer implements RandomizerInterface
{
    /** @var list<int> */
    private array $indices;

    private int $cursor = 0;

    /**
     * @param list<int> $indices
     */
    public function __construct(array $indices = [0])
    {
        $this->indices = $indices !== [] ? \array_values($indices) : [0];
    }

    public function pickIndex(int $count): int
    {
        $idx = $this->indices[$this->cursor % \count($this->indices)];
        $this->cursor++;
        return $idx % $count;
    }

    /**
     * @template T
     * @param list<T> $items
     * @return list<T>
     */
    public function shuffle(array $items): array
    {
        return \array_values($items);
    }
}
