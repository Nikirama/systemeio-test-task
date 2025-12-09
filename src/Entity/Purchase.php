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

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
