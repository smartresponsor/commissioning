<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

/**
 * Defines the public Commissioning entity contract represented by CommissionTypeEntityInterface across persistence-aware callers.
 */
interface CommissionTypeEntityInterface
{
    public function getCode(): string;

    public function getOperation(): string;
}
