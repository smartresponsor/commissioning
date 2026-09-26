<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionCalculationLineEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionCalculationLineRepositoryInterface to application services and resolvers.
 */
interface CommissionCalculationLineRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionCalculationLineEntity $line): void;
}
