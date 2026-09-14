<?php

declare(strict_types=1);

namespace App\Commissioning\EntityInterface;

interface CommissionEntityInterface
{
    public function getVendorReference(): string;

    public function getProductReference(): string;

    public function getOrderReference(): ?string;
}
