<?php

declare(strict_types=1);

namespace App\Tests\Functional\CurrencyRate;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CurrencyRateControllerTest extends WebTestCase
{
    #[DataProvider('validRequestsProvider')]
    public function testValidRequests(string $url, int $expectedStatus = Response::HTTP_OK): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($expectedStatus);

        if ($expectedStatus === Response::HTTP_OK) {
            $this->assertResponseHeaderSame('Content-Type', 'application/json');
            $data = json_decode($client->getResponse()->getContent(), true);
            $this->assertIsArray($data);
        }
    }

    public static function validRequestsProvider(): array
    {
        return [
            'last-24h with EUR/BTC' => ['/api/rates/last-24h?pair=EUR/BTC'],
            'last-24h with EUR/ETH' => ['/api/rates/last-24h?pair=EUR/ETH'],
            'last-24h with EUR/LTC' => ['/api/rates/last-24h?pair=EUR/LTC'],
            'day with valid date' => ['/api/rates/day?pair=EUR/BTC&date=2025-10-03'],
            'day with EUR/ETH' => ['/api/rates/day?pair=EUR/ETH&date=2025-10-03'],
        ];
    }

    #[DataProvider('invalidRequestsProvider')]
    public function testInvalidRequests(string $url, int $expectedStatus): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public static function invalidRequestsProvider(): array
    {
        return [
            'missing pair parameter' => ['/api/rates/last-24h', Response::HTTP_BAD_REQUEST],
            'invalid pair' => ['/api/rates/last-24h?pair=INVALID', Response::HTTP_BAD_REQUEST],
            'missing date parameter' => ['/api/rates/day?pair=EUR/BTC', Response::HTTP_BAD_REQUEST],
            'invalid date format' => ['/api/rates/day?pair=EUR/BTC&date=invalid-date', Response::HTTP_BAD_REQUEST],
            'invalid date value' => ['/api/rates/day?pair=EUR/BTC&date=2025-13-99', Response::HTTP_BAD_REQUEST],
            'invalid period' => ['/api/rates/invalid-period?pair=EUR/BTC', Response::HTTP_NOT_FOUND],
        ];
    }
}
