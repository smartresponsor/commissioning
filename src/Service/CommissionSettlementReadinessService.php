<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionSettlementReadinessResultDTO;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementReadinessServiceInterface;

final class CommissionSettlementReadinessService implements CommissionSettlementReadinessServiceInterface
{
    public function __construct(private readonly CommissionLedgerEntryRepositoryInterface $ledgerRepository)
    {
    }

    public function markReadyForBeneficiary(string $beneficiaryReference): CommissionSettlementReadinessResultDTO
    {
        $entries = $this->ledgerRepository->findPendingByBeneficiaryReference($beneficiaryReference);
        $marked = 0;

        foreach ($entries as $entry) {
            $entry->markSettlementReady();
            $this->ledgerRepository->save($entry);
            ++$marked;
        }

        return new CommissionSettlementReadinessResultDTO($marked);
    }
}
