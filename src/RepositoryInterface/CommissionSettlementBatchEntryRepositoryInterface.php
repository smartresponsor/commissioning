<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;

interface CommissionSettlementBatchEntryRepositoryInterface
{
    public function save(CommissionSettlementBatchEntryEntity $entry): void;

    public function existsForBatchAndLedgerEntry(
        CommissionSettlementBatchEntity $batch,
        CommissionLedgerEntryEntity $ledgerEntry,
    ): bool;

    /**
     * @return list<CommissionSettlementBatchEntryEntity>
     */
    public function findByBatchReference(string $batchReference): array;
}
