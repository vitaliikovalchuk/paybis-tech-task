<?php

declare(strict_types=1);

namespace App\Tests\Unit\CurrencyRate\Infrastructure\Mapper;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Exception\InvalidBinanceResponseException;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Mapper\BinanceSymbolMapper;
use PHPUnit\Framework\TestCase;

final class BinanceSymbolMapperTest extends TestCase
{
    private BinanceSymbolMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new BinanceSymbolMapper();
    }

    public function testToBinanceSymbol(): void
    {
        $this->assertSame('BTCEUR', $this->mapper->toBinanceSymbol(CurrencyPair::EUR_BTC));
        $this->assertSame('ETHEUR', $this->mapper->toBinanceSymbol(CurrencyPair::EUR_ETH));
        $this->assertSame('LTCEUR', $this->mapper->toBinanceSymbol(CurrencyPair::EUR_LTC));
    }

    public function testToBinanceSymbols(): void
    {
        $pairs = [CurrencyPair::EUR_BTC, CurrencyPair::EUR_ETH, CurrencyPair::EUR_LTC];
        $symbols = $this->mapper->toBinanceSymbols($pairs);

        $this->assertSame(['BTCEUR', 'ETHEUR', 'LTCEUR'], $symbols);
    }

    public function testToCurrencyPair(): void
    {
        $this->assertSame(CurrencyPair::EUR_BTC, $this->mapper->toCurrencyPair('BTCEUR'));
        $this->assertSame(CurrencyPair::EUR_ETH, $this->mapper->toCurrencyPair('ETHEUR'));
        $this->assertSame(CurrencyPair::EUR_LTC, $this->mapper->toCurrencyPair('LTCEUR'));
    }

    public function testToCurrencyPairThrowsExceptionForUnsupportedSymbol(): void
    {
        $this->expectException(InvalidBinanceResponseException::class);
        $this->expectExceptionMessage('Unsupported Binance symbol: USDEUR');

        $this->mapper->toCurrencyPair('USDEUR');
    }
}
