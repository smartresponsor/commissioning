<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionEntity;

interface CommissionRepositoryInterface
{
    public function save(CommissionEntity $commission): void;

    public function findActiveForVendorProduct(string $vendorReference, string $productReference): ?CommissionEntity;
}
