<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\ExternalApi\Binance\Exception;

final class InvalidBinanceResponseException extends \RuntimeException
{
    public static function missingRequiredFields(): self
    {
        return new self('Invalid Binance response format: missing required fields (symbol or price)');
    }

    public static function unsupportedSymbol(string $symbol): self
    {
        return new self(sprintf('Unsupported Binance symbol: %s', $symbol));
    }

    public static function emptyResponse(): self
    {
        return new self('No data returned from Binance API');
    }
}
