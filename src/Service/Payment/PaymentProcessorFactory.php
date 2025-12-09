<?php

namespace App\Service\Payment;

use InvalidArgumentException;

class PaymentProcessorFactory
{
    /**
     * @var array<string, PaymentProcessorInterface>
     */
    private array $processors = [];

    public function __construct(iterable $processors)
    {
        foreach ($processors as $name => $processor) {
            $this->processors[$name] = $processor;
        }
    }

    public function get(string $name): PaymentProcessorInterface
    {
        $name = strtolower($name);

        if (!isset($this->processors[$name])) {
            throw new InvalidArgumentException("Unknown payment processor: $name");
        }

        return $this->processors[$name];
    }
}
