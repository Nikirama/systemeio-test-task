<?php

namespace App\Service\Payment;

/**
 * Creating new implementation don't forget to add the new processor to DI (services.yaml)
 */
interface PaymentProcessorInterface
{
    public function pay(float $amount): bool;
}
