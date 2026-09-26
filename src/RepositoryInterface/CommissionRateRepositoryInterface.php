<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRateEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionRateRepositoryInterface to application services and resolvers.
 */
interface CommissionRateRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionRateEntity $rate): void;

    /**
     * @return list<CommissionRateEntity>
     */
    public function findActiveByPlanCode(string $planCode, ?string $currencyCode = null): array;
}
