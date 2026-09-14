<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionCalculationLineEntity;

interface CommissionCalculationLineRepositoryInterface
{
    public function save(CommissionCalculationLineEntity $line): void;
}
