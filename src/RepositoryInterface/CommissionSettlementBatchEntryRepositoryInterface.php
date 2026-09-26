<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionSettlementBatchEntryRepositoryInterface to application services and resolvers.
 */
interface CommissionSettlementBatchEntryRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionSettlementBatchEntryEntity $entry): void;

    /**
     * Performs the existsForBatchAndLedgerEntry operation defined by this typed Commissioning application contract.
     */
    public function existsForBatchAndLedgerEntry(
        CommissionSettlementBatchEntity $batch,
        CommissionLedgerEntryEntity $ledgerEntry,
    ): bool;

    /**
     * @return list<CommissionSettlementBatchEntryEntity>
     */
    public function findByBatchReference(string $batchReference): array;
}
