<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column(length: 50)]
    private string $taxNumber;

    #[ORM\Column(nullable: true, length: 50)]
    private ?string $couponCode = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $finalPrice;

    #[ORM\Column(length: 50)]
    private string $paymentProcessor;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(?string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    public function getFinalPrice(): string
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(string $price): void
    {
        $this->finalPrice = $price;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): void
    {
        $this->paymentProcessor = $paymentProcessor;
    }
}
