<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalAdapter implements PaymentProcessorInterface
{
    public function __construct(private readonly PaypalPaymentProcessor $paypalPaymentProcessor)
    {}

    public function pay(float $amount): bool
    {
        $this->paypalPaymentProcessor->pay($amount);
        return true;
    }
}
