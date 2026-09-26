<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionTypeEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionTypeRepositoryInterface to application services and resolvers.
 */
interface CommissionTypeRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionTypeEntity $entity): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByCode(string $code): ?CommissionTypeEntity;
}
