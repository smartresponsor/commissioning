<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionCalculationEntity;

interface CommissionCalculationRepositoryInterface
{
    public function save(CommissionCalculationEntity $calculation): void;

    public function findOneByEconomicEventReference(string $economicEventReference): ?CommissionCalculationEntity;
}
