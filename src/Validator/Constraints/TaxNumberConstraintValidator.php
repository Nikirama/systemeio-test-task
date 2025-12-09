<?php

namespace App\Validator\Constraints;

use App\Enum\TaxRate;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            // Let NotBlank handle empty values
            return;
        }

        $taxRate = TaxRate::fromTaxNumber($value);
        if (!$taxRate) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
