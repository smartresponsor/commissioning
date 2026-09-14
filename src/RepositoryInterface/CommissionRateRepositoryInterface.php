<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRateEntity;

interface CommissionRateRepositoryInterface
{
    public function save(CommissionRateEntity $rate): void;

    /**
     * @return list<CommissionRateEntity>
     */
    public function findActiveByPlanCode(string $planCode, ?string $currencyCode = null): array;
}
