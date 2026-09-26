<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\DTO\CommissionSettlementBatchCreateRequestDTO;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Enum\CommissionLedgerStatusEnum;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionSettlementBatchRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use App\Commissioning\Service\CommissionSettlementBatchService;
use App\Commissioning\Service\CommissionSettlementReadinessService;
use PHPUnit\Framework\TestCase;

final class CommissionSettlementWorkflowTest extends TestCase
{
    public function testReadinessMovesPendingEntriesToSettlementReady(): void
    {
        $first = $this->ledgerEntry('event-1', 'beneficiary-1', 700);
        $second = $this->ledgerEntry('event-2', 'beneficiary-1', 300);

        $repository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $repository->expects(self::once())->method('findPendingByBeneficiaryReference')->with('beneficiary-1')->willReturn([$first, $second]);
        $repository->expects(self::exactly(2))->method('save');

        $result = (new CommissionSettlementReadinessService($repository))->markReadyForBeneficiary('beneficiary-1');

        self::assertSame(2, $result->markedCount);
        self::assertSame(CommissionLedgerStatusEnum::SettlementReady, $first->getStatus());
        self::assertSame(CommissionLedgerStatusEnum::SettlementReady, $second->getStatus());
    }

    public function testBeneficiaryBatchIncludesOnlySettlementReadyEntries(): void
    {
        $included = $this->ledgerEntry('event-1', 'beneficiary-1', 700);
        $duplicate = $this->ledgerEntry('event-2', 'beneficiary-1', 300);
        $included->markSettlementReady();
        $duplicate->markSettlementReady();

        $ledgerRepository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $ledgerRepository->expects(self::once())->method('findSettlementReadyByBeneficiaryReference')->with('beneficiary-1')->willReturn([$included, $duplicate]);
        $ledgerRepository->expects(self::never())->method('findPendingByBeneficiaryReference');
        $ledgerRepository->expects(self::never())->method('findSettlementReady');

        $batchRepository = $this->createMock(CommissionSettlementBatchRepositoryInterface::class);
        $batchRepository->expects(self::once())->method('findOneByBatchReference')->with('batch-1')->willReturn(null);
        $batchRepository->expects(self::once())->method('save');

        $batchEntryRepository = $this->createMock(CommissionSettlementBatchEntryRepositoryInterface::class);
        $batchEntryRepository->expects(self::exactly(2))->method('existsForBatchAndLedgerEntry')->willReturnOnConsecutiveCalls(false, true);
        $batchEntryRepository->expects(self::once())->method('save');

        $service = new CommissionSettlementBatchService(
            $ledgerRepository,
            $batchRepository,
            $batchEntryRepository,
            $this->immediateTransactionService(),
        );

        $result = $service->createBatch(new CommissionSettlementBatchCreateRequestDTO('batch-1', 'beneficiary-1'));

        self::assertSame('batch-1', $result->batchReference);
        self::assertSame('draft', $result->status);
        self::assertSame(1, $result->entryCount);
        self::assertSame(700, $result->totalMinorAmount);
        self::assertSame(1, $result->duplicateEntryCount);
    }

    public function testUnfilteredBatchUsesGlobalSettlementReadyQuery(): void
    {
        $entry = $this->ledgerEntry('event-1', 'beneficiary-1', 450);
        $entry->markSettlementReady();

        $ledgerRepository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $ledgerRepository->expects(self::once())->method('findSettlementReady')->willReturn([$entry]);
        $ledgerRepository->expects(self::never())->method('findSettlementReadyByBeneficiaryReference');

        $batchRepository = $this->createStub(CommissionSettlementBatchRepositoryInterface::class);
        $batchRepository->method('findOneByBatchReference')->willReturn(null);

        $batchEntryRepository = $this->createStub(CommissionSettlementBatchEntryRepositoryInterface::class);
        $batchEntryRepository->method('existsForBatchAndLedgerEntry')->willReturn(false);

        $service = new CommissionSettlementBatchService(
            $ledgerRepository,
            $batchRepository,
            $batchEntryRepository,
            $this->immediateTransactionService(),
        );

        $result = $service->createBatch(new CommissionSettlementBatchCreateRequestDTO('batch-all'));

        self::assertSame(1, $result->entryCount);
        self::assertSame(450, $result->totalMinorAmount);
        self::assertSame(0, $result->duplicateEntryCount);
    }

    private function ledgerEntry(string $eventReference, string $beneficiaryReference, int $minorAmount): CommissionLedgerEntryEntity
    {
        $plan = new CommissionPlanEntity('plan-'.$eventReference, 'Plan '.$eventReference);
        $calculation = new CommissionCalculationEntity($plan, $eventReference, 'USD', 10_000, $minorAmount);

        return new CommissionLedgerEntryEntity($calculation, $beneficiaryReference, 'USD', $minorAmount);
    }

    private function immediateTransactionService(): CommissionTransactionRepositoryInterface
    {
        $service = $this->createStub(CommissionTransactionRepositoryInterface::class);
        $service->method('transactional')->willReturnCallback(static fn (callable $callback): mixed => $callback());

        return $service;
    }
}
