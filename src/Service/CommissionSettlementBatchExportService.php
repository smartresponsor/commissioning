<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionSettlementBatchExportDTO;
use App\Commissioning\DTO\CommissionSettlementEntryExportDTO;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchExportServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionSettlementBatchExportService across typed collaborators and boundaries.
 */
final class CommissionSettlementBatchExportService implements CommissionSettlementBatchExportServiceInterface
{
    public function __construct(
        private readonly CommissionSettlementBatchRepositoryInterface $batchRepository,
        private readonly CommissionSettlementBatchEntryRepositoryInterface $batchEntryRepository,
    ) {
    }

    /**
     * Exports canonical Commissioning settlement data through the typed application handoff contract.
     */
    public function exportBatch(string $batchReference): CommissionSettlementBatchExportDTO
    {
        $batch = $this->batchRepository->findOneByBatchReference($batchReference);

        if (null === $batch) {
            throw new \InvalidArgumentException(sprintf('Commission settlement batch "%s" was not found.', $batchReference));
        }

        $batchEntries = $this->batchEntryRepository->findByBatchReference($batchReference);
        $entries = [];
        $total = 0;

        foreach ($batchEntries as $batchEntry) {
            $ledgerEntry = $batchEntry->getLedgerEntry();
            $total += $ledgerEntry->getMinorAmount();

            $entries[] = new CommissionSettlementEntryExportDTO(
                beneficiaryReference: $ledgerEntry->getBeneficiaryReference(),
                currencyCode: $ledgerEntry->getCurrencyCode(),
                minorAmount: $ledgerEntry->getMinorAmount(),
                sourceStatus: $ledgerEntry->getStatus()->value,
            );
        }

        $batch->markExported();
        $this->batchRepository->save($batch);

        return new CommissionSettlementBatchExportDTO(
            batchReference: $batch->getBatchReference(),
            status: $batch->getStatus()->value,
            entryCount: count($entries),
            totalMinorAmount: $total,
            entries: $entries,
        );
    }
}
