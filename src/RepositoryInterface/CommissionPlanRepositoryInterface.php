<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionPlanEntity;

interface CommissionPlanRepositoryInterface
{
    public function save(CommissionPlanEntity $plan): void;

    public function findOneByCode(string $code): ?CommissionPlanEntity;
}
