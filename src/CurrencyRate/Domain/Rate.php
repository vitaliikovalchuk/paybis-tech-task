<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain;

final readonly class Rate
{
    public function __construct(
        private string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
