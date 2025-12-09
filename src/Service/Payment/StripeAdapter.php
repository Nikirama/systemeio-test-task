<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeAdapter implements PaymentProcessorInterface
{
    public function __construct(private readonly StripePaymentProcessor $stripePaymentProcessor)
    {}

    public function pay(float $amount): bool
    {
        return $this->stripePaymentProcessor->processPayment($amount);
    }
}
