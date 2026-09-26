<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Contract;

use App\Commissioning\DTO\CommissionApiCalculationResponseDTO;
use App\Commissioning\DTO\CommissionApiRecordResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchExportResponseDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyResponseDTO;
use App\Commissioning\DTO\CommissionBeneficiaryInputDTO;
use App\Commissioning\DTO\CommissionCalculationLineDTO;
use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\DTO\CommissionCalculationResultDTO;
use App\Commissioning\DTO\CommissionRuleInputDTO;
use App\Commissioning\DTO\CommissionRuntimeCheckResultDTO;
use App\Commissioning\DTO\CommissionRuntimeReportDTO;
use App\Commissioning\DTO\CommissionSettlementEntryExportDTO;
use App\Commissioning\DTO\CommissionSettlementExportDTO;
use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\Event\CommissionCalculatedEvent;
use App\Commissioning\EventSubscriber\CommissionWorkflowSubscriber;
use App\Commissioning\Policy\CommissionLifecyclePolicy;
use App\Commissioning\ValueObject\CommissionBeneficiaryValueObject;
use App\Commissioning\ValueObject\CommissionIdempotencyKeyValueObject;
use App\Commissioning\ValueObject\CommissionMoneyValueObject;
use App\Commissioning\ValueObject\CommissionRateValueObject;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;
use PHPUnit\Framework\TestCase;

final class CommissionContractSurfaceTest extends TestCase
{
    public function testApiAndRuntimeDtosPreserveBoundaryData(): void
    {
        $calculation = new CommissionApiCalculationResponseDTO('event-1', 'USD', 10_000, 750, 'calculated');
        $record = new CommissionApiRecordResponseDTO('event-1', 'USD', 750, 'pending', true);
        $batchRequest = new CommissionApiSettlementBatchCreateRequestDTO('batch-1', 'beneficiary-1');
        $batchResponse = new CommissionApiSettlementBatchCreateResponseDTO('batch-1', 'draft', 2, 1_000, 1);
        $entry = new CommissionSettlementEntryExportDTO('beneficiary-1', 'USD', 1_000, 'settlement_ready');
        $batchExport = new CommissionApiSettlementBatchExportResponseDTO('batch-1', 'exported', 1, 1_000, [$entry]);
        $readyRequest = new CommissionApiSettlementReadyRequestDTO('beneficiary-1');
        $readyResponse = new CommissionApiSettlementReadyResponseDTO(3);
        $beneficiary = new CommissionBeneficiaryInputDTO('partner', 'beneficiary-1', 'Partner One');
        $line = new CommissionCalculationLineDTO('fixed_commission', 'USD', 250, 'fixed');
        $calculationRequest = new CommissionCalculationRequestDTO('default', 'event-1', 'USD', 10_000, 'attr-1');
        $rule = new CommissionRuleInputDTO('region', 'equals', 'US', 10);
        $check = new CommissionRuntimeCheckResultDTO('database', 'green', ['connected']);
        $report = new CommissionRuntimeReportDTO('Commissioning', 'green', [$check]);
        $settlement = new CommissionSettlementExportDTO('batch-1', [['amount' => 1000, 'currency' => 'USD']]);

        self::assertSame(750, $calculation->commissionMinorAmount);
        self::assertTrue($record->duplicate);
        self::assertSame('beneficiary-1', $batchRequest->beneficiaryReference);
        self::assertSame(1, $batchResponse->duplicateEntryCount);
        self::assertSame([$entry], $batchExport->entries);
        self::assertSame('beneficiary-1', $readyRequest->beneficiaryReference);
        self::assertSame(3, $readyResponse->markedCount);
        self::assertSame('Partner One', $beneficiary->displayName);
        self::assertSame(250, $line->minorAmount);
        self::assertSame('attr-1', $calculationRequest->attributionReference);
        self::assertSame(10, $rule->priority);
        self::assertSame(['connected'], $check->messages);
        self::assertSame([$check], $report->checks);
        self::assertSame('batch-1', $settlement->batchReference);
    }

    public function testValueObjectsAndCalculatedEventPreserveTypedState(): void
    {
        $money = new CommissionMoneyValueObject('USD', 500);
        $beneficiary = new CommissionBeneficiaryValueObject(
            CommissionBeneficiaryTypeEnum::Partner,
            'partner-1',
            'Partner One',
        );
        $rate = new CommissionRateValueObject(CommissionRateTypeEnum::Hybrid, '5.5', $money);
        $context = new CommissionRuleContextValueObject(['region' => 'US', 'amount' => 1000]);
        $result = new CommissionCalculationResultDTO('event-1', 'USD', 10_000, 750, 'calculated');
        $event = new CommissionCalculatedEvent($result);
        $key = new CommissionIdempotencyKeyValueObject('event-1');

        self::assertSame('USD', $money->currencyCode);
        self::assertSame(500, $money->minorAmount);
        self::assertSame('partner-1', $beneficiary->reference);
        self::assertSame($money, $rate->fixedAmount);
        self::assertSame('US', $context->get('region'));
        self::assertNull($context->get('missing'));
        self::assertSame(['region' => 'US', 'amount' => 1000], $context->toArray());
        self::assertSame($result, $event->result);
        self::assertSame('event-1', $key->value);
    }

    public function testIdempotencyKeyRejectsBlankValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CommissionIdempotencyKeyValueObject('   ');
    }

    public function testLifecyclePolicyNormalizesAndGuardsTransitions(): void
    {
        self::assertTrue(CommissionLifecyclePolicy::canTransition(' DRAFT ', 'CALCULATED'));
        self::assertTrue(CommissionLifecyclePolicy::canTransition('settled', 'settled'));
        self::assertFalse(CommissionLifecyclePolicy::canTransition('settled', 'draft'));

        CommissionLifecyclePolicy::assertCanTransition('approved', 'settlement_pending');
        self::assertContains('settled', CommissionLifecyclePolicy::knownStates());
    }

    public function testLifecyclePolicyRejectsInvalidTransition(): void
    {
        $this->expectException(\DomainException::class);

        CommissionLifecyclePolicy::assertCanTransition('settled', 'draft');
    }

    public function testWorkflowSubscriberHasNoImplicitFrameworkEvents(): void
    {
        self::assertSame([], CommissionWorkflowSubscriber::getSubscribedEvents());
    }
}
