<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\DTO\CommissionCalculationLineResultDTO;
use App\Commissioning\DTO\CommissionIdempotencyResultDTO;
use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\RepositoryInterface\CommissionCalculationLineRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use App\Commissioning\Service\CommissionCalculationRecordService;
use App\Commissioning\ServiceInterface\CommissionIdempotencyServiceInterface;
use PHPUnit\Framework\TestCase;

final class CommissionCalculationRecordServiceTest extends TestCase
{
    public function testDuplicateEventReturnsExistingAmountWithoutAnyWrites(): void
    {
        $request = $this->request();
        $existing = new CommissionCalculationEntity(
            new CommissionPlanEntity('plan-1', 'Plan 1'),
            'event-1',
            'USD',
            10_000,
            625,
        );

        $planRepository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $planRepository->expects(self::never())->method('findOneByCode');
        $planRepository->expects(self::never())->method('save');

        $calculationRepository = $this->createMock(CommissionCalculationRepositoryInterface::class);
        $calculationRepository
            ->expects(self::once())
            ->method('findOneByEconomicEventReference')
            ->with('event-1')
            ->willReturn($existing);
        $calculationRepository->expects(self::never())->method('save');

        $lineRepository = $this->createMock(CommissionCalculationLineRepositoryInterface::class);
        $lineRepository->expects(self::never())->method('save');

        $ledgerRepository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $ledgerRepository->expects(self::never())->method('save');

        $idempotency = $this->createMock(CommissionIdempotencyServiceInterface::class);
        $idempotency
            ->expects(self::once())
            ->method('checkEconomicEvent')
            ->with('event-1')
            ->willReturn(new CommissionIdempotencyResultDTO(true, 'event-1'));

        $transaction = $this->createMock(CommissionTransactionRepositoryInterface::class);
        $transaction->expects(self::never())->method('transactional');

        $result = (new CommissionCalculationRecordService(
            $planRepository,
            $calculationRepository,
            $lineRepository,
            $ledgerRepository,
            $idempotency,
            $transaction,
        ))->record($request);

        self::assertTrue($result->duplicate);
        self::assertSame('duplicate', $result->ledgerStatus);
        self::assertSame(625, $result->commissionMinorAmount);
        self::assertSame('event-1', $result->economicEventReference);
    }

    public function testNewEventPersistsPlanCalculationLinesAndLedgerInsideTransaction(): void
    {
        $request = $this->request();

        $planRepository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $planRepository
            ->expects(self::once())
            ->method('findOneByCode')
            ->with('plan-1')
            ->willReturn(null);
        $planRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(static fn (CommissionPlanEntity $plan): bool => 'plan-1' === $plan->getCode()));

        $calculationRepository = $this->createMock(CommissionCalculationRepositoryInterface::class);
        $calculationRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(static fn (CommissionCalculationEntity $calculation): bool => 'event-1' === $calculation->getEconomicEventReference()
                && 10_000 === $calculation->getBasisMinorAmount()
                && 500 === $calculation->getCommissionMinorAmount()
            ));

        $savedLines = [];
        $lineRepository = $this->createMock(CommissionCalculationLineRepositoryInterface::class);
        $lineRepository
            ->expects(self::exactly(2))
            ->method('save')
            ->willReturnCallback(static function (CommissionCalculationLineEntity $line) use (&$savedLines): void {
                $savedLines[] = $line;
            });

        $ledgerRepository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $ledgerRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(static fn (CommissionLedgerEntryEntity $entry): bool => 'beneficiary-1' === $entry->getBeneficiaryReference()
                && 'USD' === $entry->getCurrencyCode()
                && 500 === $entry->getMinorAmount()
                && 'pending' === $entry->getStatus()->value
            ));

        $idempotency = $this->createMock(CommissionIdempotencyServiceInterface::class);
        $idempotency
            ->expects(self::once())
            ->method('checkEconomicEvent')
            ->with('event-1')
            ->willReturn(new CommissionIdempotencyResultDTO(false, 'event-1'));

        $transaction = $this->createMock(CommissionTransactionRepositoryInterface::class);
        $transaction
            ->expects(self::once())
            ->method('transactional')
            ->willReturnCallback(static fn (callable $callback): mixed => $callback());

        $result = (new CommissionCalculationRecordService(
            $planRepository,
            $calculationRepository,
            $lineRepository,
            $ledgerRepository,
            $idempotency,
            $transaction,
        ))->record($request);

        self::assertFalse($result->duplicate);
        self::assertSame('pending', $result->ledgerStatus);
        self::assertSame(500, $result->commissionMinorAmount);
        self::assertCount(2, $savedLines);
        self::assertSame(400, $savedLines[0]->getMinorAmount());
        self::assertSame(100, $savedLines[1]->getMinorAmount());
    }

    public function testExistingPlanIsReusedWithoutSavingAnotherPlan(): void
    {
        $request = $this->request();
        $existingPlan = new CommissionPlanEntity('plan-1', 'Existing plan');

        $planRepository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $planRepository->expects(self::once())->method('findOneByCode')->with('plan-1')->willReturn($existingPlan);
        $planRepository->expects(self::never())->method('save');

        $calculationRepository = $this->createMock(CommissionCalculationRepositoryInterface::class);
        $calculationRepository->expects(self::once())->method('save');

        $lineRepository = $this->createMock(CommissionCalculationLineRepositoryInterface::class);
        $lineRepository->expects(self::exactly(2))->method('save');

        $ledgerRepository = $this->createMock(CommissionLedgerEntryRepositoryInterface::class);
        $ledgerRepository->expects(self::once())->method('save');

        $idempotency = $this->createStub(CommissionIdempotencyServiceInterface::class);
        $idempotency->method('checkEconomicEvent')->willReturn(new CommissionIdempotencyResultDTO(false, 'event-1'));

        $transaction = $this->createStub(CommissionTransactionRepositoryInterface::class);
        $transaction->method('transactional')->willReturnCallback(static fn (callable $callback): mixed => $callback());

        $result = (new CommissionCalculationRecordService(
            $planRepository,
            $calculationRepository,
            $lineRepository,
            $ledgerRepository,
            $idempotency,
            $transaction,
        ))->record($request);

        self::assertFalse($result->duplicate);
        self::assertSame('pending', $result->ledgerStatus);
    }

    private function request(): CommissionRecordCalculationRequestDTO
    {
        return new CommissionRecordCalculationRequestDTO(
            planCode: 'plan-1',
            planName: 'Plan 1',
            economicEventReference: 'event-1',
            currencyCode: 'USD',
            basisMinorAmount: 10_000,
            commissionMinorAmount: 500,
            beneficiaryReference: 'beneficiary-1',
            lines: [
                new CommissionCalculationLineResultDTO('percentage_commission', 'USD', 400, 'percentage'),
                new CommissionCalculationLineResultDTO('fixed_commission', 'USD', 100, 'fixed'),
            ],
        );
    }
}
