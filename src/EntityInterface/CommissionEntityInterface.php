<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

/**
 * Defines the public Commissioning entity contract represented by CommissionEntityInterface across persistence-aware callers.
 */
interface CommissionEntityInterface
{
    public function getVendorReference(): string;

    public function getProductReference(): string;

    public function getOrderReference(): ?string;
}
