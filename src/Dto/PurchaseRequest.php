<?php

namespace App\Dto;

use App\Validator\Constraints\PaymentProcessorConstraint;
use App\Validator\Constraints\TaxNumberConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class PurchaseRequest
{
    public function __construct(
        #[Assert\Type('integer')]
        public int $productId,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[TaxNumberConstraint]
        public string $taxNumber,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[PaymentProcessorConstraint]
        public string $paymentProcessor,

        #[Assert\Type('string')]
        public ?string $couponCode
    ) {}
}
