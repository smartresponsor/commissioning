<?php

declare(strict_types=1);

namespace App\Commissioning\RepositoryInterface;

use App\Commissioning\Entity\CommissionLedgerEntryEntity;

interface CommissionLedgerEntryRepositoryInterface
{
    public function save(CommissionLedgerEntryEntity $entry): void;

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findPendingByBeneficiaryReference(string $beneficiaryReference): array;

    /**
     * @return list<CommissionLedgerEntryEntity>
     */
    public function findSettlementReady(): array;
}
