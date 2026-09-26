<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionDirectionEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionDirectionRepositoryInterface to application services and resolvers.
 */
interface CommissionDirectionRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionDirectionEntity $entity): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByCode(string $code): ?CommissionDirectionEntity;
}
