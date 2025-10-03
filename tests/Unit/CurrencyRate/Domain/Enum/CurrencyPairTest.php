<?php

declare(strict_types=1);

namespace App\Tests\Unit\CurrencyRate\Domain\Enum;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use PHPUnit\Framework\TestCase;

final class CurrencyPairTest extends TestCase
{
    public function testHasAllExpectedPairs(): void
    {
        $pairs = CurrencyPair::cases();

        $this->assertCount(3, $pairs);
        $this->assertContains(CurrencyPair::EUR_BTC, $pairs);
        $this->assertContains(CurrencyPair::EUR_ETH, $pairs);
        $this->assertContains(CurrencyPair::EUR_LTC, $pairs);
    }

    public function testCanGetPairValues(): void
    {
        $this->assertSame('EUR/BTC', CurrencyPair::EUR_BTC->value);
        $this->assertSame('EUR/ETH', CurrencyPair::EUR_ETH->value);
        $this->assertSame('EUR/LTC', CurrencyPair::EUR_LTC->value);
    }

    public function testCanCreateFromString(): void
    {
        $this->assertSame(CurrencyPair::EUR_BTC, CurrencyPair::from('EUR/BTC'));
        $this->assertSame(CurrencyPair::EUR_ETH, CurrencyPair::from('EUR/ETH'));
        $this->assertSame(CurrencyPair::EUR_LTC, CurrencyPair::from('EUR/LTC'));
    }

    public function testThrowsExceptionForInvalidPair(): void
    {
        $this->expectException(\ValueError::class);
        CurrencyPair::from('INVALID');
    }

    public function testGetAllValues(): void
    {
        $values = CurrencyPair::getAllValues();

        $this->assertCount(3, $values);
        $this->assertContains('EUR/BTC', $values);
        $this->assertContains('EUR/ETH', $values);
        $this->assertContains('EUR/LTC', $values);
    }
}
