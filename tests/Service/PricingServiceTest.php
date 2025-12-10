<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\TaxRate;
use App\Repository\ProductRepository;
use App\Repository\CouponRepository;
use App\Service\PricingService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Я не имею реального опыта с юнит-тестами, но общее представление, конечно, имею
 * Можно было бы добавить тесты самих api эндпоинтов, помимо сервисов
 * И больше кейсов
 */
class PricingServiceTest extends TestCase
{
    private PricingService $service;
    private $productRepository;
    private $couponRepository;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->couponRepository = $this->createMock(CouponRepository::class);

        $this->service = new PricingService(
            $this->productRepository,
            $this->couponRepository
        );
    }

    public function testCalculateWithoutCoupon(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $this->productRepository
            ->method('find')
            ->with(1)
            ->willReturn($product);

        $final = $this->service->calculateFinalPrice(1, 'DE123456789', null);
        $this->assertEquals(119, $final); // 100 + 19% tax
    }

    public function testCalculateWithPercentCoupon(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setType(Coupon::TYPE_PERCENT);
        $coupon->setValue(10); // 10%

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);

        $final = $this->service->calculateFinalPrice(1, 'DE123456789', 'P10');
        $this->assertEquals(107.1, $final); // 100 - 10% = 90 + 19% = 107.1
    }

    public function testCalculateWithFixedCoupon(): void
    {
        $product = new Product();
        $product->setPrice(50);

        $coupon = new Coupon();
        $coupon->setType(Coupon::TYPE_FIXED);
        $coupon->setValue(20); // 20 EUR

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);

        $final = $this->service->calculateFinalPrice(1, 'DE123456789', 'D20');
        $this->assertEquals(35.7, $final); // 50-20=30 +19% = 35.7
    }

    public function testProductNotFound(): void
    {
        $this->productRepository->method('find')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->calculateFinalPrice(999, 'DE123456789', null);
    }

    public function testInvalidTaxNumber(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $this->productRepository->method('find')->willReturn($product);

        $this->expectException(InvalidArgumentException::class);
        $this->service->calculateFinalPrice(1, 'XX123', null);
    }

    public function testCouponNotFound(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $this->productRepository->method('find')->willReturn($product);

        $this->couponRepository->method('findOneBy')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->calculateFinalPrice(1, 'DE123456789', 'NOTEXIST');
    }
}
