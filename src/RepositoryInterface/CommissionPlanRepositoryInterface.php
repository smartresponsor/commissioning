<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionPlanEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionPlanRepositoryInterface to application services and resolvers.
 */
interface CommissionPlanRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionPlanEntity $plan): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByCode(string $code): ?CommissionPlanEntity;
}
