<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionSettlementBatchEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionSettlementBatchRepositoryInterface to application services and resolvers.
 */
interface CommissionSettlementBatchRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionSettlementBatchEntity $batch): void;

    /**
     * Finds Commissioning records matching the supplied criteria for the calling application collaborator.
     */
    public function findOneByBatchReference(string $batchReference): ?CommissionSettlementBatchEntity;
}
