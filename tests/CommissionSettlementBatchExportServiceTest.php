<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchRepositoryInterface;
use App\Commissioning\Service\CommissionSettlementBatchExportService;
use PHPUnit\Framework\TestCase;

final class CommissionSettlementBatchExportServiceTest extends TestCase
{
    public function testExportProducesPayoutHandoffAndMarksBatchExported(): void
    {
        $batch = new CommissionSettlementBatchEntity('batch-1');
        $first = $this->ledgerEntry('event-1', 'beneficiary-1', 700);
        $second = $this->ledgerEntry('event-2', 'beneficiary-2', 300);
        $first->markSettlementReady();
        $second->markSettlementReady();

        $batchRepository = $this->createMock(CommissionSettlementBatchRepositoryInterface::class);
        $batchRepository
            ->expects(self::once())
            ->method('findOneByBatchReference')
            ->with('batch-1')
            ->willReturn($batch);
        $batchRepository
            ->expects(self::once())
            ->method('save')
            ->with($batch);

        $batchEntryRepository = $this->createMock(CommissionSettlementBatchEntryRepositoryInterface::class);
        $batchEntryRepository
            ->expects(self::once())
            ->method('findByBatchReference')
            ->with('batch-1')
            ->willReturn([
                new CommissionSettlementBatchEntryEntity($batch, $first),
                new CommissionSettlementBatchEntryEntity($batch, $second),
            ]);

        $result = (new CommissionSettlementBatchExportService($batchRepository, $batchEntryRepository))
            ->exportBatch('batch-1');

        self::assertSame('batch-1', $result->batchReference);
        self::assertSame('exported', $result->status);
        self::assertSame(2, $result->entryCount);
        self::assertSame(1_000, $result->totalMinorAmount);
        self::assertCount(2, $result->entries);

        self::assertSame('beneficiary-1', $result->entries[0]->beneficiaryReference);
        self::assertSame('USD', $result->entries[0]->currencyCode);
        self::assertSame(700, $result->entries[0]->minorAmount);
        self::assertSame('settlement_ready', $result->entries[0]->sourceStatus);

        self::assertSame('beneficiary-2', $result->entries[1]->beneficiaryReference);
        self::assertSame(300, $result->entries[1]->minorAmount);
        self::assertSame('settlement_ready', $result->entries[1]->sourceStatus);
        self::assertSame('exported', $batch->getStatus()->value);
    }

    public function testMissingBatchFailsBeforeReadingEntriesOrSaving(): void
    {
        $batchRepository = $this->createMock(CommissionSettlementBatchRepositoryInterface::class);
        $batchRepository
            ->expects(self::once())
            ->method('findOneByBatchReference')
            ->with('missing-batch')
            ->willReturn(null);
        $batchRepository->expects(self::never())->method('save');

        $batchEntryRepository = $this->createMock(CommissionSettlementBatchEntryRepositoryInterface::class);
        $batchEntryRepository->expects(self::never())->method('findByBatchReference');

        $service = new CommissionSettlementBatchExportService($batchRepository, $batchEntryRepository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Commission settlement batch "missing-batch" was not found.');

        $service->exportBatch('missing-batch');
    }

    private function ledgerEntry(
        string $eventReference,
        string $beneficiaryReference,
        int $minorAmount,
    ): CommissionLedgerEntryEntity {
        $plan = new CommissionPlanEntity('plan-'.$eventReference, 'Plan '.$eventReference);
        $calculation = new CommissionCalculationEntity(
            $plan,
            $eventReference,
            'USD',
            10_000,
            $minorAmount,
        );

        return new CommissionLedgerEntryEntity(
            $calculation,
            $beneficiaryReference,
            'USD',
            $minorAmount,
        );
    }
}
