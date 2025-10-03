<?php

declare(strict_types=1);

namespace App\CurrencyRate\Presentation\Responder\Json;

use App\CurrencyRate\Domain\CurrencyRates;
use JsonSerializable;

final readonly class CurrencyRatesResponder implements JsonSerializable
{
    public function __construct(
        private CurrencyRates $currencyRates,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'data' => array_map(
                fn($rate) => [
                    'id' => $rate->getId()->toString(),
                    'currency_pair' => $rate->getPairRate()->getPair()->value,
                    'rate' => $rate->getPairRate()->getRate()->getValue(),
                    'timestamp' => $rate->getTimestamp()->format('Y-m-d H:i:s'),
                ],
                $this->currencyRates->getItems()
            ),
            'count' => $this->currencyRates->count(),
        ];
    }
}
