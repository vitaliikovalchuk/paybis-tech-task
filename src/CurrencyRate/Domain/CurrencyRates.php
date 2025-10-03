<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain;

use App\CurrencyRate\Domain\CurrencyRate;

final readonly class CurrencyRates
{
    /**
     * @param CurrencyRate[] $items
     */
    public function __construct(
        private array $items = [],
    ) {
    }

    /**
     * @return CurrencyRate[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
