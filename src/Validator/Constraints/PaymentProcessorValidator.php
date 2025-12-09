<?php

namespace App\Validator\Constraints;

use App\Service\Payment\PaymentProcessorFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentProcessorValidator extends ConstraintValidator
{
    public function __construct(private PaymentProcessorFactory $factory) {}

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PaymentProcessorConstraint) {
            return;
        }

        if ($value === null) {
            return;
        }

        try {
            $this->factory->get($value);
        } catch (\Throwable) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
