<?php

namespace App\Controller;

use App\Attribute\MapRequestPayload;
use App\Dto\CalculatePriceRequest;
use App\Dto\PurchaseRequest;
use App\Service\PricingService;
use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly PricingService $pricingService,
        private readonly PurchaseService $purchaseService,
    ) {}

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(#[MapRequestPayload] CalculatePriceRequest $request): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'price' => $this->pricingService->calculateFinalPrice($request->productId, $request->taxNumber, $request->couponCode),
        ]);
    }

    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(#[MapRequestPayload] PurchaseRequest $request): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'result' => $this->purchaseService->purchase(
                $request->productId,
                $request->taxNumber,
                $request->paymentProcessor,
                $request->couponCode,
            ),
        ]);
    }
}
