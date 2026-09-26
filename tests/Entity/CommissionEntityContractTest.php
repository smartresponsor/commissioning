<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Entity;

use App\Commissioning\Entity\CommissionAttributionEntity;
use App\Commissioning\Entity\CommissionBeneficiaryEntity;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\Entity\CommissionDirectionEntity;
use App\Commissioning\Entity\CommissionEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionRuleEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use App\Commissioning\Entity\CommissionTierEntity;
use App\Commissioning\Entity\CommissionTypeEntity;
use App\Commissioning\Enum\CommissionAttributionSourceTypeEnum;
use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;
use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\Enum\CommissionLedgerStatusEnum;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\Enum\CommissionRuleOperatorEnum;
use App\Commissioning\Enum\CommissionSettlementBatchStatusEnum;
use PHPUnit\Framework\TestCase;

final class CommissionEntityContractTest extends TestCase
{
    public function testPlanLifecycleAndIdentityAccessors(): void
    {
        $plan = new CommissionPlanEntity('standard', 'Standard plan');

        self::assertNull($plan->getId());
        self::assertSame('standard', $plan->getCode());
        self::assertSame('Standard plan', $plan->getName());
        self::assertTrue($plan->isActive());

        $plan->deactivate();

        self::assertFalse($plan->isActive());
    }

    public function testDirectionExposesConfiguredTargets(): void
    {
        $direction = new CommissionDirectionEntity(
            code: 'all',
            nameEntity: 'All targets',
            toShipment: true,
            toPayment: true,
            toPrice: true,
            toDate: true,
            toPlatformReward: true,
            toStorage: true,
            toProjectType: true,
            toOrderTotal: true,
            toProductCategory: true,
        );

        self::assertNull($direction->getId());
        self::assertSame('all', $direction->getCode());
        self::assertSame('All targets', $direction->getName());
        self::assertTrue($direction->targetsShipment());
        self::assertTrue($direction->targetsPayment());
        self::assertTrue($direction->targetsPrice());
        self::assertTrue($direction->targetsDate());
        self::assertTrue($direction->targetsPlatformReward());
        self::assertTrue($direction->targetsStorage());
        self::assertTrue($direction->targetsProjectType());
        self::assertTrue($direction->targetsOrderTotal());
        self::assertTrue($direction->targetsProductCategory());
    }

    public function testTypeAndCommissionRootExposeConfiguredContract(): void
    {
        $plan = new CommissionPlanEntity('standard', 'Standard plan');
        $type = new CommissionTypeEntity('sale', 'Sale', 'calculate');
        $direction = new CommissionDirectionEntity('payment', 'Payment', toPayment: true);
        $effectiveFrom = new \DateTimeImmutable('2026-01-01T00:00:00+00:00');
        $effectiveUntil = new \DateTimeImmutable('2026-12-31T23:59:59+00:00');

        $commission = new CommissionEntity(
            plan: $plan,
            type: $type,
            direction: $direction,
            vendorReference: 'vendor-1',
            productReference: 'product-1',
            percentage: '12.50',
            amount: '25.00',
            currencyCode: 'usd',
            orderReference: 'order-1',
            effectiveFrom: $effectiveFrom,
            effectiveUntil: $effectiveUntil,
        );

        self::assertNull($type->getId());
        self::assertSame('sale', $type->getCode());
        self::assertSame('Sale', $type->getName());
        self::assertSame('calculate', $type->getOperation());

        self::assertNull($commission->getId());
        self::assertSame($plan, $commission->getPlan());
        self::assertSame($type, $commission->getType());
        self::assertSame($direction, $commission->getDirection());
        self::assertSame('vendor-1', $commission->getVendorReference());
        self::assertSame('product-1', $commission->getProductReference());
        self::assertSame('order-1', $commission->getOrderReference());
        self::assertSame('12.50', $commission->getPercentage());
        self::assertSame('25.00', $commission->getAmount());
        self::assertSame('USD', $commission->getCurrencyCode());
        self::assertSame($effectiveFrom, $commission->getEffectiveFrom());
        self::assertSame($effectiveUntil, $commission->getEffectiveUntil());
    }

    public function testRateRuleAndTierContracts(): void
    {
        $plan = new CommissionPlanEntity('tiered', 'Tiered plan');
        $rate = new CommissionRateEntity(
            $plan,
            CommissionRateTypeEnum::Tiered,
            '9.5',
            125,
            'USD',
        );
        $rule = new CommissionRuleEntity(
            $plan,
            'region',
            CommissionRuleOperatorEnum::Equals,
            'US',
            10,
        );
        $tier = new CommissionTierEntity($rate, 1_000, 5_000, '7.5');
        $openTier = new CommissionTierEntity($rate, 5_001, null, '10.0');

        self::assertNull($rate->getId());
        self::assertSame($plan, $rate->getPlan());
        self::assertSame(CommissionRateTypeEnum::Tiered, $rate->getType());
        self::assertSame('9.5', $rate->getPercentageRate());
        self::assertSame(125, $rate->getFixedMinorAmount());
        self::assertSame('USD', $rate->getCurrencyCode());
        self::assertTrue($rate->isActive());

        self::assertSame('region', $rule->getRuleKey());
        self::assertSame(CommissionRuleOperatorEnum::Equals, $rule->getOperator());
        self::assertSame('US', $rule->getExpectedValue());
        self::assertTrue($rule->isActive());

        self::assertSame(1_000, $tier->getMinimumMinorAmount());
        self::assertSame(5_000, $tier->getMaximumMinorAmount());
        self::assertSame('7.5', $tier->getPercentageRate());
        self::assertFalse($tier->matches(999));
        self::assertTrue($tier->matches(1_000));
        self::assertTrue($tier->matches(5_000));
        self::assertFalse($tier->matches(5_001));
        self::assertNull($openTier->getMaximumMinorAmount());
        self::assertTrue($openTier->matches(10_000));
    }

    public function testCalculationLedgerAndSettlementLifecycle(): void
    {
        $plan = new CommissionPlanEntity('standard', 'Standard plan');
        $calculation = new CommissionCalculationEntity($plan, 'event-1', 'USD', 10_000, 750);
        $line = new CommissionCalculationLineEntity(
            $calculation,
            CommissionCalculationLineTypeEnum::PercentageCommission,
            'USD',
            750,
            '7.5 percent',
        );
        $ledger = new CommissionLedgerEntryEntity($calculation, 'beneficiary-1', 'USD', 750);
        $batch = new CommissionSettlementBatchEntity('batch-1');
        $batchEntry = new CommissionSettlementBatchEntryEntity($batch, $ledger);

        self::assertNull($calculation->getId());
        self::assertSame('event-1', $calculation->getEconomicEventReference());
        self::assertSame('USD', $calculation->getCurrencyCode());
        self::assertSame(10_000, $calculation->getBasisMinorAmount());
        self::assertSame(750, $calculation->getCommissionMinorAmount());
        self::assertSame('calculated', $calculation->getStatus()->value);
        self::assertSame(750, $line->getMinorAmount());

        self::assertNull($ledger->getId());
        self::assertSame($calculation, $ledger->getCalculation());
        self::assertSame('beneficiary-1', $ledger->getBeneficiaryReference());
        self::assertSame('USD', $ledger->getCurrencyCode());
        self::assertSame(750, $ledger->getMinorAmount());
        self::assertSame(CommissionLedgerStatusEnum::Pending, $ledger->getStatus());

        $ledger->markSettlementReady();
        self::assertSame(CommissionLedgerStatusEnum::SettlementReady, $ledger->getStatus());
        $ledger->markSettled();
        self::assertSame(CommissionLedgerStatusEnum::Settled, $ledger->getStatus());
        $ledger->reverse();
        self::assertSame(CommissionLedgerStatusEnum::Reversed, $ledger->getStatus());

        self::assertNull($batch->getId());
        self::assertSame('batch-1', $batch->getBatchReference());
        self::assertSame(CommissionSettlementBatchStatusEnum::Draft, $batch->getStatus());
        $batch->markExported();
        self::assertSame(CommissionSettlementBatchStatusEnum::Exported, $batch->getStatus());
        $batch->markAccepted();
        self::assertSame(CommissionSettlementBatchStatusEnum::Accepted, $batch->getStatus());
        $batch->cancel();
        self::assertSame(CommissionSettlementBatchStatusEnum::Cancelled, $batch->getStatus());

        self::assertSame($batch, $batchEntry->getBatch());
        self::assertSame($ledger, $batchEntry->getLedgerEntry());
    }

    public function testBeneficiaryAndAttributionConstructorsRepresentSupportedIdentityTypes(): void
    {
        $beneficiary = new CommissionBeneficiaryEntity(
            CommissionBeneficiaryTypeEnum::Partner,
            'partner-1',
            'Partner One',
        );
        $attribution = new CommissionAttributionEntity(
            CommissionAttributionSourceTypeEnum::Referral,
            'referral-1',
            'event-1',
        );

        self::assertSame('partner-1', $beneficiary->getBeneficiaryReference());
        self::assertTrue($beneficiary->isActive());
        self::assertInstanceOf(CommissionAttributionEntity::class, $attribution);
    }
}
