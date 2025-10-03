<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\ExternalApi;

use App\CurrencyRate\Application\Contract\RateProviderInterface;
use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Domain\PairRates;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Exception\InvalidBinanceResponseException;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Mapper\BinanceResponseMapper;
use App\CurrencyRate\Infrastructure\ExternalApi\Binance\Mapper\BinanceSymbolMapper;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class CryptoRateApiClient implements RateProviderInterface
{
    private const string BINANCE_API_URL = 'https://api.binance.com/api/v3/ticker/price';

    public function __construct(
        private HttpClientInterface $httpClient,
        private BinanceSymbolMapper $symbolMapper,
        private BinanceResponseMapper $responseMapper,
        private LoggerInterface $logger
    ) {}

    /**
     * @param CurrencyPair[] $pairs
     */
    public function fetchRates(array $pairs): PairRates
    {
        $binanceSymbols = $this->symbolMapper->toBinanceSymbols($pairs);

        $response = $this->httpClient->request('GET', self::BINANCE_API_URL, [
            'query' => [
                'symbols' => json_encode($binanceSymbols),
            ],
        ]);

        $data = $response->toArray();

        if (empty($data)) {
            throw InvalidBinanceResponseException::emptyResponse();
        }

        return $this->responseMapper->toPairRates($data);
    }
}
