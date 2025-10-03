<?php

declare(strict_types=1);

namespace App\CurrencyRate\Presentation\Controller;

use App\CurrencyRate\Application\Service\FindCurrencyRateService;
use App\CurrencyRate\Presentation\RequestHandler\CurrencyRateRequestHandler;
use App\CurrencyRate\Presentation\Responder\Json\CurrencyRatesResponder;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rates')]
class CurrencyRateController extends AbstractController
{
    public function __construct(
        private readonly CurrencyRateRequestHandler $requestHandler,
        private readonly FindCurrencyRateService $service,
    ) {}

    #[Route('/{period}', name: 'api_rates_by_period', requirements: ['period' => 'last-24h|day'], methods: ['GET'])]
    public function getRatesByPeriod(Request $request): JsonResponse
    {
        try {
            $currencyRates = $this->service->find(
                $this->requestHandler->handle($request)
            );

            return $this->json(new CurrencyRatesResponder($currencyRates));
        } catch (InvalidArgumentException $e) {
            throw new BadRequestException($e->getMessage());
        }
    }
}
