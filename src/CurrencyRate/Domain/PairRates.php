<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain;

final readonly class PairRates
{
    /**
     * @param PairRate[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    /**
     * @return PairRate[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
