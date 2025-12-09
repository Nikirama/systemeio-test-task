<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class TaxNumberConstraint extends Constraint
{
    public string $message = 'Invalid tax number format.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
