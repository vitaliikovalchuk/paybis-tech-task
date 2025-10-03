<?php

declare(strict_types=1);

namespace App\CurrencyRate\Application\SearchRequest;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use DateTimeInterface;

final readonly class CurrencyRateSearchRequest
{
    private function __construct(
        public ?string $period = null,
        public ?DateTimeInterface $date = null,
        public ?CurrencyPair $pair = null,
    ) {
    }

    public static function byPeriod(string $period, CurrencyPair $pair): self
    {
        return new self($period, null, $pair);
    }

    public static function byPeriodAndDate(string $period, DateTimeInterface $date, CurrencyPair $pair): self
    {
        return new self($period, $date, $pair);
    }
}
