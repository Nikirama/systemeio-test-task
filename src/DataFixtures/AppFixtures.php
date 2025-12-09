<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Products
        $iphone = (new Product())
            ->setName('iPhone')
            ->setPrice(100);
        $manager->persist($iphone);

        $headphones = (new Product())
            ->setName('Headphones')
            ->setPrice(20);
        $manager->persist($headphones);

        $case = (new Product())
            ->setName('Case')
            ->setPrice(10);
        $manager->persist($case);

        // Coupons
        // Percentage coupon (10%)
        $couponP10 = (new Coupon())
            ->setCode('P10')
            ->setType(Coupon::TYPE_PERCENT)
            ->setValue(10);
        $manager->persist($couponP10);

        // Percentage coupon (100%)
        $couponP100 = (new Coupon())
            ->setCode('P100')
            ->setType(Coupon::TYPE_PERCENT)
            ->setValue(100);
        $manager->persist($couponP100);

        // Fixed discount coupon (15 euros)
        $couponD15 = (new Coupon())
            ->setCode('F15')
            ->setType(Coupon::TYPE_FIXED)
            ->setValue(15);
        $manager->persist($couponD15);

        $manager->flush();
    }
}
