<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function create(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
        string $finalPrice,
        string $paymentProcessor
    ): Purchase {
        $purchase = new Purchase();
        $purchase->setProduct($product);
        $purchase->setTaxNumber($taxNumber);
        $purchase->setCouponCode($couponCode);
        $purchase->setFinalPrice($finalPrice);
        $purchase->setPaymentProcessor($paymentProcessor);

        return $purchase;
    }
}
