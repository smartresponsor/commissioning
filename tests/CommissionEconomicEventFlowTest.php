<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionAttributionResolutionResultDTO;
use App\Commissioning\DTO\CommissionBeneficiaryResolutionResultDTO;
use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionCalculationLineResultDTO;
use App\Commissioning\DTO\CommissionEconomicEventDTO;
use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionPlanResolutionResultDTO;
use App\Commissioning\DTO\CommissionRateInputDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;
use App\Commissioning\ResolverInterface\CommissionAttributionResolverInterface;
use App\Commissioning\ResolverInterface\CommissionBeneficiaryResolverInterface;
use App\Commissioning\ResolverInterface\CommissionPlanResolverInterface;
use App\Commissioning\ResolverInterface\CommissionRateResolverInterface;
use App\Commissioning\Service\CommissionEconomicEventCalculationService;
use App\Commissioning\Service\CommissionEconomicEventRecordService;
use App\Commissioning\ServiceInterface\CommissionCalculationRecordServiceInterface;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;
use PHPUnit\Framework\TestCase;

final class CommissionEconomicEventFlowTest extends TestCase
{
    public function testCalculationFlowPropagatesResolutionContextIntoEngine(): void
    {
        $event = $this->event();

        $attributionResolver = $this->createMock(CommissionAttributionResolverInterface::class);
        $attributionResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(self::callback(static fn (CommissionAttributionResolutionRequestDTO $request): bool => 'event-1' === $request->economicEventReference
                && 'order' === $request->sourceType
                && 'order-44' === $request->sourceReference
                && ['channel' => 'direct'] === $request->context
            ))
            ->willReturn(new CommissionAttributionResolutionResultDTO('order', 'order-44', 'event-1'));

        $planResolver = $this->createMock(CommissionPlanResolverInterface::class);
        $planResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(self::callback(static fn (CommissionPlanResolutionRequestDTO $request): bool => 'plan-requested' === $request->planCode
                && ['channel' => 'direct'] === $request->context
            ))
            ->willReturn(new CommissionPlanResolutionResultDTO('plan-resolved', 'Resolved plan'));

        $rate = new CommissionRateInputDTO('percentage', percentageRate: '7.5', currencyCode: 'USD');
        $rateResolver = $this->createMock(CommissionRateResolverInterface::class);
        $rateResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(self::callback(static fn (CommissionRateResolutionRequestDTO $request): bool => 'plan-resolved' === $request->planCode
                && 'USD' === $request->currencyCode
                && 20_000 === $request->basisMinorAmount
                && ['channel' => 'direct'] === $request->context
            ))
            ->willReturn($rate);

        $engine = $this->createMock(CommissionCalculationEngineInterface::class);
        $engine
            ->expects(self::once())
            ->method('calculate')
            ->with(
                self::callback(static fn (CommissionBasisValueObject $basis): bool => 'event-1' === $basis->economicEventReference
                    && 'order-44' === $basis->attributionReference
                    && 'USD' === $basis->money->currencyCode
                    && 20_000 === $basis->money->minorAmount
                ),
                $rate,
            )
            ->willReturn(new CommissionCalculationEngineResultDTO(
                currencyCode: 'USD',
                basisMinorAmount: 20_000,
                commissionMinorAmount: 1_500,
                lines: [],
            ));

        $result = (new CommissionEconomicEventCalculationService(
            $planResolver,
            $rateResolver,
            $attributionResolver,
            $engine,
        ))->calculateForEconomicEvent($event);

        self::assertSame('event-1', $result->economicEventReference);
        self::assertSame('USD', $result->currencyCode);
        self::assertSame(20_000, $result->basisMinorAmount);
        self::assertSame(1_500, $result->commissionMinorAmount);
        self::assertSame('calculated', $result->status);
    }

    public function testRecordFlowBuildsBeneficiaryContextAndPersistsEngineOutput(): void
    {
        $event = $this->event();
        $line = new CommissionCalculationLineResultDTO('percentage_commission', 'USD', 1_500, '7.5%');

        $attributionResolver = $this->createStub(CommissionAttributionResolverInterface::class);
        $attributionResolver
            ->method('resolve')
            ->willReturn(new CommissionAttributionResolutionResultDTO('order', 'order-44', 'event-1'));

        $beneficiaryResolver = $this->createMock(CommissionBeneficiaryResolverInterface::class);
        $beneficiaryResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(self::callback(static fn (CommissionRuleContextValueObject $context): bool => [
                'channel' => 'direct',
                'beneficiary_reference' => 'order-44',
                'beneficiary_type' => 'order',
            ] === $context->toArray()
            ))
            ->willReturn(new CommissionBeneficiaryResolutionResultDTO('salesperson', 'seller-9', 'Seller 9'));

        $planResolver = $this->createStub(CommissionPlanResolverInterface::class);
        $planResolver->method('resolve')->willReturn(new CommissionPlanResolutionResultDTO('plan-resolved', 'Resolved plan'));

        $rate = new CommissionRateInputDTO('percentage', percentageRate: '7.5', currencyCode: 'USD');
        $rateResolver = $this->createMock(CommissionRateResolverInterface::class);
        $rateResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(self::callback(static fn (CommissionRateResolutionRequestDTO $request): bool => 'plan-resolved' === $request->planCode
                && 20_000 === $request->basisMinorAmount
            ))
            ->willReturn($rate);

        $engine = $this->createMock(CommissionCalculationEngineInterface::class);
        $engine
            ->expects(self::once())
            ->method('calculate')
            ->with(
                self::callback(static fn (CommissionBasisValueObject $basis): bool => 'event-1' === $basis->economicEventReference
                    && 'order-44' === $basis->attributionReference
                ),
                $rate,
            )
            ->willReturn(new CommissionCalculationEngineResultDTO(
                currencyCode: 'USD',
                basisMinorAmount: 20_000,
                commissionMinorAmount: 1_500,
                lines: [$line],
            ));

        $recordService = $this->createMock(CommissionCalculationRecordServiceInterface::class);
        $recordService
            ->expects(self::once())
            ->method('record')
            ->with(self::callback(static fn (CommissionRecordCalculationRequestDTO $request): bool => 'plan-resolved' === $request->planCode
                && 'Resolved plan' === $request->planName
                && 'event-1' === $request->economicEventReference
                && 'USD' === $request->currencyCode
                && 20_000 === $request->basisMinorAmount
                && 1_500 === $request->commissionMinorAmount
                && 'seller-9' === $request->beneficiaryReference
                && [$line] === $request->lines
            ))
            ->willReturn(new CommissionRecordCalculationResultDTO(
                economicEventReference: 'event-1',
                currencyCode: 'USD',
                commissionMinorAmount: 1_500,
                ledgerStatus: 'pending',
            ));

        $result = (new CommissionEconomicEventRecordService(
            $planResolver,
            $rateResolver,
            $attributionResolver,
            $beneficiaryResolver,
            $engine,
            $recordService,
        ))->recordEconomicEvent($event);

        self::assertFalse($result->duplicate);
        self::assertSame('event-1', $result->economicEventReference);
        self::assertSame(1_500, $result->commissionMinorAmount);
        self::assertSame('pending', $result->ledgerStatus);
    }

    private function event(): CommissionEconomicEventDTO
    {
        return new CommissionEconomicEventDTO(
            eventReference: 'event-1',
            currencyCode: 'USD',
            basisMinorAmount: 20_000,
            planCode: 'plan-requested',
            attributionSourceType: 'order',
            attributionSourceReference: 'order-44',
            context: ['channel' => 'direct'],
        );
    }
}
