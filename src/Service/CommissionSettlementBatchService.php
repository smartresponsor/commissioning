<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionSettlementBatchCreateResultDTO;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionSettlementBatchServiceInterface;
use App\Commissioning\ServiceInterface\CommissionTransactionServiceInterface;

final class CommissionSettlementBatchService implements CommissionSettlementBatchServiceInterface
{
    public function __construct(
        private readonly CommissionLedgerEntryRepositoryInterface $ledgerRepository,
        private readonly CommissionSettlementBatchRepositoryInterface $batchRepository,
        private readonly CommissionSettlementBatchEntryRepositoryInterface $batchEntryRepository,
        private readonly CommissionTransactionServiceInterface $transactionService,
    ) {
    }

    public function createBatch(CommissionSettlementBatchCreateRequestDTO $request): CommissionSettlementBatchCreateResultDTO
    {
        return $this->transactionService->transactional(function () use ($request): CommissionSettlementBatchCreateResultDTO {
            $batch = $this->batchRepository->findOneByBatchReference($request->batchReference);

            if (!$batch instanceof CommissionSettlementBatchEntity) {
                $batch = new CommissionSettlementBatchEntity($request->batchReference);
                $this->batchRepository->save($batch);
            }

            $ledgerEntries = null !== $request->beneficiaryReference
                ? $this->ledgerRepository->findPendingByBeneficiaryReference($request->beneficiaryReference)
                : $this->ledgerRepository->findSettlementReady();

            $count = 0;
            $duplicates = 0;
            $total = 0;

            foreach ($ledgerEntries as $ledgerEntry) {
                if ($this->batchEntryRepository->existsForBatchAndLedgerEntry($batch, $ledgerEntry)) {
                    ++$duplicates;
                    continue;
                }

                $batchEntry = new CommissionSettlementBatchEntryEntity($batch, $ledgerEntry);
                $this->batchEntryRepository->save($batchEntry);
                $total += $ledgerEntry->getMinorAmount();
                ++$count;
            }

            return new CommissionSettlementBatchCreateResultDTO(
                batchReference: $batch->getBatchReference(),
                status: $batch->getStatus()->value,
                entryCount: $count,
                totalMinorAmount: $total,
                duplicateEntryCount: $duplicates,
            );
        });
    }
}
