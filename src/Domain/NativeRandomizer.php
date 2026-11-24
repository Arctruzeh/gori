<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;

final class NativeRandomizer implements RandomizerInterface
{
    public function pickIndex(int $count): int
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('Count must be greater than zero.');
        }

        return \random_int(0, $count - 1);
    }

    /**
     * @template T
     * @param list<T> $items
     * @return list<T>
     */
    public function shuffle(array $items): array
    {
        $list = \array_values($items);
        $n = \count($list);

        for ($i = $n - 1; $i > 0; $i--) {
            $j = \random_int(0, $i);
            if ($i !== $j) {
                [$list[$i], $list[$j]] = [$list[$j], $list[$i]];
            }
        }

        return $list;
    }
}
