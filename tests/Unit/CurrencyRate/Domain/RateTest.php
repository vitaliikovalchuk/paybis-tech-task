<?php

declare(strict_types=1);

namespace App\Tests\Unit\CurrencyRate\Domain;

use App\CurrencyRate\Domain\Rate;
use PHPUnit\Framework\TestCase;

final class RateTest extends TestCase
{
    public function testCanCreateRate(): void
    {
        $rate = new Rate('102622.56000000');

        $this->assertSame('102622.56000000', $rate->getValue());
    }

    public function testRateIsImmutable(): void
    {
        $rate = new Rate('102622.56000000');
        $value = $rate->getValue();

        $this->assertSame('102622.56000000', $value);
        $this->assertSame($value, $rate->getValue());
    }

    public function testCanCreateRateWithDifferentPrecision(): void
    {
        $rate = new Rate('0.00000001');

        $this->assertSame('0.00000001', $rate->getValue());
    }
}
