<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class PaymentProcessorConstraint extends Constraint
{
    public string $message = 'Unknown payment processor "{{ value }}".';

    public function validatedBy(): string
    {
        return PaymentProcessorValidator::class;
    }
}
