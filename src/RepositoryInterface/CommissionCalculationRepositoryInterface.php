<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionCalculationEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionCalculationRepositoryInterface to application services and resolvers.
 */
interface CommissionCalculationRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionCalculationEntity $calculation): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByEconomicEventReference(string $economicEventReference): ?CommissionCalculationEntity;
}
