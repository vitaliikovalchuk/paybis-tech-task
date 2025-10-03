<?php

declare(strict_types=1);

namespace App\Tests\Unit\CurrencyRate\Presentation\Validator;

use App\CurrencyRate\Presentation\RequestHandler\Validator\CurrencyRateRequestValidator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CurrencyRateRequestValidatorTest extends TestCase
{
    private CurrencyRateRequestValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CurrencyRateRequestValidator();
    }

    public function testValidateSucceedsForLast24Hours(): void
    {
        $request = Request::create('/api/rates/last-24h?pair=EUR/BTC');
        $request->attributes->set('period', 'last-24h');

        $this->validator->validate($request);
        $this->assertTrue(true); // No exception thrown
    }

    public function testValidateSucceedsForDayWithDate(): void
    {
        $request = Request::create('/api/rates/day?pair=EUR/BTC&date=2025-10-03');
        $request->attributes->set('period', 'day');

        $this->validator->validate($request);
        $this->assertTrue(true); // No exception thrown
    }

    public function testValidateFailsWhenPairIsMissing(): void
    {
        $request = Request::create('/api/rates/last-24h');
        $request->attributes->set('period', 'last-24h');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateFailsForInvalidPair(): void
    {
        $request = Request::create('/api/rates/last-24h?pair=INVALID');
        $request->attributes->set('period', 'last-24h');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateFailsForInvalidPeriod(): void
    {
        $request = Request::create('/api/rates/invalid?pair=EUR/BTC');
        $request->attributes->set('period', 'invalid');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateFailsForDayWithoutDate(): void
    {
        $request = Request::create('/api/rates/day?pair=EUR/BTC');
        $request->attributes->set('period', 'day');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateFailsForInvalidDateFormat(): void
    {
        $request = Request::create('/api/rates/day?pair=EUR/BTC&date=invalid');
        $request->attributes->set('period', 'day');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateFailsForInvalidDateValue(): void
    {
        $request = Request::create('/api/rates/day?pair=EUR/BTC&date=2025-13-99');
        $request->attributes->set('period', 'day');

        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($request);
    }

    public function testValidateAcceptsAllSupportedPairs(): void
    {
        $pairs = ['EUR/BTC', 'EUR/ETH', 'EUR/LTC'];

        foreach ($pairs as $pair) {
            $request = Request::create('/api/rates/last-24h?pair=' . urlencode($pair));
            $request->attributes->set('period', 'last-24h');

            $this->validator->validate($request);
            $this->assertTrue(true); // No exception thrown
        }
    }
}
