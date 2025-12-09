<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Enum\TaxRate;
use App\Repository\ProductRepository;
use App\Repository\CouponRepository;
use InvalidArgumentException;

class PricingService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CouponRepository  $couponRepository,
    ) {}

    public function calculateFinalPrice(int $productId, string $taxNumber, ?string $couponCode): float
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new InvalidArgumentException('Product not found');
        }

        $taxRate = TaxRate::fromTaxNumber($taxNumber);
        if (!$taxRate) {
            throw new InvalidArgumentException('Invalid tax number');
        }

        $basePrice = $product->getPrice();

        $discounted = $basePrice;
        if ($couponCode) {
            $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);
            if (!$coupon) {
                throw new InvalidArgumentException('Coupon not found');
            }

            /**
             * Можно было бы отдельный калькулятор придумать на будущее,
             * Для разных категорий товаров, разного ценового диапазона и тд
             * Много чего считать надо в реальных условиях
             * Но для тестового задания пусть так будет
             */
            if ($coupon->getType() === Coupon::TYPE_PERCENT) {
                $discounted -= $basePrice * ($coupon->getValue() / 100);
            } else {
                $discounted -= $coupon->getValue();
            }
        }

        /**
         * Мало ли
         */
        if ($discounted < 0) {
            $discounted = 0;
        }

        $taxAmount = $discounted * ($taxRate->rate() / 100);
        return round($discounted + $taxAmount, 2);
    }
}
