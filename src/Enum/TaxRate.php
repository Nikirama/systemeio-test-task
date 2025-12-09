<?php

namespace App\Enum;

enum TaxRate: string
{
    case Germany = 'DE';
    case Italy   = 'IT';
    case France  = 'FR';
    case Greece  = 'GR';

    public function countryName(): string
    {
        return match($this) {
            self::Germany => 'Germany',
            self::Italy => 'Italy',
            self::France => 'France',
            self::Greece => 'Greece',
        };
    }

    public function rate(): float
    {
        return match($this) {
            self::Germany => 19.0,
            self::Italy => 22.0,
            self::France => 20.0,
            self::Greece => 24.0,
        };
    }

    public function regex(): string
    {
        return match($this) {
            self::Germany => '/^DE[0-9]{9}$/',
            self::Italy => '/^IT[0-9]{11}$/',
            self::France => '/^FR[A-Z]{2}[0-9]{9}$/',
            self::Greece => '/^GR[0-9]{9}$/',
        };
    }

    public static function fromTaxNumber(string $taxNumber): ?self
    {
        $prefix = strtoupper(substr($taxNumber, 0, 2));
        foreach (self::cases() as $case) {
            if ($case->value === $prefix && preg_match($case->regex(), $taxNumber)) {
                return $case;
            }
        }
        return null;
    }
}
