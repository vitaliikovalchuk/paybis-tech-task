<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\ExternalApi\Binance\Mapper;

use App\CurrencyRate\Domain\PairRate;
use App\CurrencyRate\Domain\PairRates;
use App\CurrencyRate\Domain\Rate;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Exception\InvalidBinanceResponseException;

final readonly class BinanceResponseMapper
{
    public function __construct(
        private BinanceSymbolMapper $symbolMapper
    ) {
    }

    /**
     * @param array<int, array{symbol: string, price: string}> $responses
     */
    public function toPairRates(array $responses): PairRates
    {
        $result = [];

        foreach ($responses as $response) {
            $symbol = $response['symbol'] ?? null;
            $price = $response['price'] ?? null;

            if ($symbol === null || $price === null) {
                throw InvalidBinanceResponseException::missingRequiredFields();
            }

            $pair = $this->symbolMapper->toCurrencyPair($symbol);

            $result[] = new PairRate(
                pair: $pair,
                rate: new Rate($price)
            );
        }

        return new PairRates($result);
    }
}
