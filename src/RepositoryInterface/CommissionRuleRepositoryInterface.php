<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRuleEntity;

interface CommissionRuleRepositoryInterface
{
    public function save(CommissionRuleEntity $rule): void;

    /**
     * @return list<CommissionRuleEntity>
     */
    public function findActiveByPlanCodeOrdered(string $planCode): array;
}
