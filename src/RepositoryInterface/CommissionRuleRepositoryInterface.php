<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRuleEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionRuleRepositoryInterface to application services and resolvers.
 */
interface CommissionRuleRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionRuleEntity $rule): void;

    /**
     * @return list<CommissionRuleEntity>
     */
    public function findActiveByPlanCodeOrdered(string $planCode): array;
}
