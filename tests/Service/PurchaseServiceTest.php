<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Service\Payment\PaymentProcessorInterface;
use App\Service\PricingService;
use App\Service\PurchaseService;
use App\Service\Payment\PaymentProcessorFactory;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PurchaseServiceTest extends TestCase
{
    private $pricingService;
    private $factory;
    private $productRepository;
    private $purchaseRepository;
    private $entityManager;
    private PurchaseService $service;

    protected function setUp(): void
    {
        $this->pricingService = $this->createMock(PricingService::class);
        $this->factory = $this->createMock(PaymentProcessorFactory::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->purchaseRepository = $this->createMock(PurchaseRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->service = new PurchaseService(
            $this->pricingService,
            $this->factory,
            $this->productRepository,
            $this->purchaseRepository,
            $this->entityManager
        );
    }

    public function testSuccessfulPurchase(): void
    {
        $product = new Product();
        $this->productRepository->method('find')->willReturn($product);
        $this->pricingService->method('calculateFinalPrice')->willReturn(119.0);

        $mockProcessor = $this->getMockBuilder(PaymentProcessorInterface::class)
            ->onlyMethods(['pay'])
            ->getMock();
        $mockProcessor->method('pay')->willReturn(true);

        $this->factory->method('get')->willReturn($mockProcessor);

        $purchase = new Purchase();
        $this->purchaseRepository->expects($this->once())
            ->method('create')
            ->willReturn($purchase);

        $this->entityManager->expects($this->once())
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->service->purchase(1, 'DE123456789', 'paypal', null);
        $this->assertSame(['status' => 'ok', 'amount' => 119.0], $result);
    }

    public function testPaymentFailure(): void
    {
        $product = new Product();
        $this->productRepository->method('find')->willReturn($product);
        $this->pricingService->method('calculateFinalPrice')->willReturn(119.0);

        $mockProcessor = $this->getMockBuilder(PaymentProcessorInterface::class)
            ->onlyMethods(['pay'])
            ->getMock();
        $mockProcessor->method('pay')->willReturn(false);

        $this->factory->method('get')->willReturn($mockProcessor);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Payment failed');

        $this->service->purchase(1, 'DE123456789', 'paypal', null);
    }

    public function testProductNotFound(): void
    {
        $this->productRepository->method('find')->willReturn(null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Product not found');

        $this->service->purchase(999, 'DE123456789', 'paypal', null);
    }
}
