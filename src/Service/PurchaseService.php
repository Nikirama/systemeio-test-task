<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\Payment\PaymentProcessorFactory;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class PurchaseService
{
    public function __construct(
        private PricingService $pricingService,
        private PaymentProcessorFactory $factory,
        private ProductRepository $productRepository,
        private PurchaseRepository $purchaseRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function purchase(
        int $productId,
        string $taxNumber,
        string $processor,
        ?string $couponCode
    ): array {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new RuntimeException('Product not found');
        }

        $price = $this->pricingService->calculateFinalPrice($productId, $taxNumber, $couponCode);

        $paymentProcessor = $this->factory->get($processor);

        $success = $paymentProcessor->pay($price);
        if (!$success) {
            throw new RuntimeException('Payment failed');
        }


        $purchase = $this->purchaseRepository->create(
            $product,
            $taxNumber,
            $couponCode,
            $price,
            $processor,
        );
        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        return [
            'status' => 'ok',
            'amount' => $price,
        ];
    }
}
