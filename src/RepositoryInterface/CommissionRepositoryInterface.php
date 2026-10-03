<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\Commission\CommissionEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionRepositoryInterface to application services and resolvers.
 */
interface CommissionRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionEntity $commission): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findActiveForVendorProduct(string $vendorReference, string $productReference): ?CommissionEntity;
}
