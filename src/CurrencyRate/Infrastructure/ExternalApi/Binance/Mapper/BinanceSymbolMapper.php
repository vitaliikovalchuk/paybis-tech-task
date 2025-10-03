<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\ExternalApi\Binance\Mapper;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Exception\InvalidBinanceResponseException;

final readonly class BinanceSymbolMapper
{
    /**
     * @param CurrencyPair[] $pairs
     * @return string[]
     */
    public function toBinanceSymbols(array $pairs): array
    {
        return array_map(
            fn(CurrencyPair $pair) => $this->toBinanceSymbol($pair),
            $pairs
        );
    }

    public function toBinanceSymbol(CurrencyPair $pair): string
    {
        return match ($pair) {
            CurrencyPair::EUR_BTC => 'BTCEUR',
            CurrencyPair::EUR_ETH => 'ETHEUR',
            CurrencyPair::EUR_LTC => 'LTCEUR',
        };
    }

    public function toCurrencyPair(string $binanceSymbol): CurrencyPair
    {
        return match ($binanceSymbol) {
            'BTCEUR' => CurrencyPair::EUR_BTC,
            'ETHEUR' => CurrencyPair::EUR_ETH,
            'LTCEUR' => CurrencyPair::EUR_LTC,
            default => throw InvalidBinanceResponseException::unsupportedSymbol($binanceSymbol),
        };
    }
}
