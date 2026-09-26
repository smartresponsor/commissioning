<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;

/**
 * Defines the Commissioning persistence contract exposed by CommissionLedgerEntryRepositoryInterface to application services and resolvers.
 */
interface CommissionLedgerEntryRepositoryInterface
{
    /**
     * Persists the supplied Commissioning record through this repository persistence boundary.
     */
    public function save(CommissionLedgerEntryEntity $entry): void;

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findPendingByBeneficiaryReference(string $beneficiaryReference): array;

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findSettlementReadyByBeneficiaryReference(string $beneficiaryReference): array;

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findSettlementReady(): array;
}
