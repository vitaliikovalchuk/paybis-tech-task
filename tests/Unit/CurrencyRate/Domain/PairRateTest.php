<?php

declare(strict_types=1);

namespace App\Tests\Unit\CurrencyRate\Domain;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Domain\PairRate;
use App\CurrencyRate\Domain\Rate;
use PHPUnit\Framework\TestCase;

final class PairRateTest extends TestCase
{
    public function testCanCreatePairRate(): void
    {
        $pair = CurrencyPair::EUR_BTC;
        $rate = new Rate('102622.56000000');

        $pairRate = new PairRate($pair, $rate);

        $this->assertSame($pair, $pairRate->getPair());
        $this->assertSame($rate, $pairRate->getRate());
    }

    public function testPairRateIsImmutable(): void
    {
        $pair = CurrencyPair::EUR_ETH;
        $rate = new Rate('3804.80000000');

        $pairRate = new PairRate($pair, $rate);

        $this->assertSame($pair, $pairRate->getPair());
        $this->assertSame($rate, $pairRate->getRate());
    }

    public function testCanAccessPairValue(): void
    {
        $pairRate = new PairRate(CurrencyPair::EUR_LTC, new Rate('100.62000000'));

        $this->assertSame('EUR/LTC', $pairRate->getPair()->value);
        $this->assertSame('100.62000000', $pairRate->getRate()->getValue());
    }
}
