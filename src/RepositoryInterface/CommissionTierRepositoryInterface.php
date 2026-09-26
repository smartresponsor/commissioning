<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionTierEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionTierRepositoryInterface to application services and resolvers.
 */
interface CommissionTierRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionTierEntity $tier): void;

    /**
     * @return list<CommissionTierEntity>
     */
    public function findByRate(CommissionRateEntity $rate): array;
}
