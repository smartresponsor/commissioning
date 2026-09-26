<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Service;

use App\Commissioning\CalculatorInterface\CommissionCalculationEngineInterface;
use App\Commissioning\DTO\CommissionApiCalculationRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementBatchCreateRequestDTO;
use App\Commissioning\DTO\CommissionApiSettlementReadyRequestDTO;
use App\Commissioning\DTO\CommissionAttributionResolutionRequestDTO;
use App\Commissioning\DTO\CommissionCalculationEngineResultDTO;
use App\Commissioning\DTO\CommissionCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionRuleEntity;
use App\Commissioning\Entity\CommissionTierEntity;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\Enum\CommissionRuleOperatorEnum;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRuleRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use App\Commissioning\Resolver\CommissionAttributionResolver;
use App\Commissioning\Resolver\CommissionBeneficiaryResolver;
use App\Commissioning\Resolver\CommissionRateResolver;
use App\Commissioning\Service\CommissionApiRequestMappingService;
use App\Commissioning\Service\CommissionCalculationService;
use App\Commissioning\Service\CommissionDemoRouteGuardService;
use App\Commissioning\Service\CommissionIdempotencyService;
use App\Commissioning\Service\CommissionRuleSetEvaluationService;
use App\Commissioning\Service\CommissionSettlementExportService;
use App\Commissioning\Service\CommissionTransactionService;
use App\Commissioning\ServiceInterface\CommissionRuleEvaluationServiceInterface;
use App\Commissioning\ValueObject\CommissionBasisValueObject;
use App\Commissioning\ValueObject\CommissionRuleContextValueObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CommissionCoreServiceContractTest extends TestCase
{
    public function testApiRequestMappingPreservesCalculationAndSettlementInputs(): void
    {
        $service = new CommissionApiRequestMappingService();
        $calculation = new CommissionApiCalculationRequestDTO(
            eventReference: 'event-1',
            currencyCode: 'USD',
            basisMinorAmount: 10_000,
            planCode: 'plan-1',
            attributionSourceType: 'partner',
            attributionSourceReference: 'partner-1',
            context: ['region' => 'US'],
        );

        $event = $service->mapCalculationRequest($calculation);
        $ready = $service->mapSettlementReadyRequest(new CommissionApiSettlementReadyRequestDTO('beneficiary-1'));
        $batch = $service->mapSettlementBatchCreateRequest(
            new CommissionApiSettlementBatchCreateRequestDTO('batch-1', 'beneficiary-1'),
        );

        self::assertSame('event-1', $event->eventReference);
        self::assertSame('plan-1', $event->planCode);
        self::assertSame(['region' => 'US'], $event->context);
        self::assertSame('beneficiary-1', $ready);
        self::assertSame('batch-1', $batch->batchReference);
        self::assertSame('beneficiary-1', $batch->beneficiaryReference);
    }

    public function testCalculationServiceBuildsCanonicalPercentageRateAndResult(): void
    {
        $engine = $this->createMock(CommissionCalculationEngineInterface::class);
        $engine->expects(self::once())->method('calculate')
            ->with(
                self::callback(static fn (CommissionBasisValueObject $basis): bool => 'event-1' === $basis->economicEventReference
                    && 'USD' === $basis->money->currencyCode
                    && 20_000 === $basis->money->minorAmount
                    && 'attr-1' === $basis->attributionReference
                ),
                self::callback(static fn ($rate): bool => 'percentage' === $rate->rateType
                    && '10' === $rate->percentageRate
                    && 'USD' === $rate->currencyCode
                ),
            )
            ->willReturn(new CommissionCalculationEngineResultDTO('USD', 20_000, 2_000, []));

        $result = (new CommissionCalculationService($engine))->calculate(
            new CommissionCalculationRequestDTO('plan-1', 'event-1', 'USD', 20_000, 'attr-1'),
        );

        self::assertSame('event-1', $result->economicEventReference);
        self::assertSame(2_000, $result->commissionMinorAmount);
        self::assertSame('calculated', $result->status);
    }

    public function testDemoRouteGuardAllowsDevAndTestButRejectsProduction(): void
    {
        (new CommissionDemoRouteGuardService('dev'))->assertDemoRouteAllowed();
        (new CommissionDemoRouteGuardService('test'))->assertDemoRouteAllowed();
        self::addToAssertionCount(2);

        $this->expectException(NotFoundHttpException::class);
        (new CommissionDemoRouteGuardService('prod'))->assertDemoRouteAllowed();
    }

    public function testIdempotencyServiceReportsExistingAndNewEvents(): void
    {
        $repository = $this->createMock(CommissionCalculationRepositoryInterface::class);
        $repository->expects(self::exactly(2))->method('findOneByEconomicEventReference')
            ->willReturnOnConsecutiveCalls(null, $this->createStub(\App\Commissioning\Entity\CommissionCalculationEntity::class));

        $service = new CommissionIdempotencyService($repository);

        self::assertFalse($service->checkEconomicEvent('new-event')->duplicate);
        self::assertTrue($service->checkEconomicEvent('existing-event')->duplicate);
    }

    public function testRuleSetEvaluationShortCircuitsOnMismatchAndAcceptsMatchingRules(): void
    {
        $plan = new CommissionPlanEntity('plan-1', 'Plan 1');
        $rule = new CommissionRuleEntity($plan, 'region', CommissionRuleOperatorEnum::Equals, 'US');

        $repository = $this->createMock(CommissionRuleRepositoryInterface::class);
        $repository->expects(self::exactly(2))->method('findActiveByPlanCodeOrdered')->with('plan-1')->willReturn([$rule]);

        $evaluator = $this->createMock(CommissionRuleEvaluationServiceInterface::class);
        $evaluator->expects(self::exactly(2))->method('matches')->willReturnOnConsecutiveCalls(true, false);

        $service = new CommissionRuleSetEvaluationService($repository, $evaluator);

        self::assertTrue($service->matchesPlanRules('plan-1', ['region' => 'US']));
        self::assertFalse($service->matchesPlanRules('plan-1', ['region' => 'CA']));
    }

    public function testSettlementExportAndTransactionDelegationRemainThinBoundaries(): void
    {
        $export = (new CommissionSettlementExportService())->exportSettlementReady('batch-1');
        self::assertSame('batch-1', $export->batchReference);
        self::assertSame([], $export->entries);

        $repository = $this->createMock(CommissionTransactionRepositoryInterface::class);
        $repository->expects(self::once())->method('transactional')
            ->willReturnCallback(static fn (callable $callback): mixed => $callback());

        $result = (new CommissionTransactionService($repository))->transactional(static fn (): string => 'ok');
        self::assertSame('ok', $result);
    }

    public function testAttributionAndBeneficiaryResolversSupportExplicitAndFallbackContext(): void
    {
        $attributionResolver = new CommissionAttributionResolver();
        $explicit = $attributionResolver->resolve(
            new CommissionAttributionResolutionRequestDTO('event-1', 'affiliate', 'affiliate-1'),
        );
        $fallback = $attributionResolver->resolve(
            new CommissionAttributionResolutionRequestDTO(
                'event-2',
                null,
                null,
                ['attribution_source_type' => 'referral', 'attribution_source_reference' => 'ref-1'],
            ),
        );

        self::assertSame('affiliate', $explicit->sourceType);
        self::assertSame('affiliate-1', $explicit->sourceReference);
        self::assertSame('referral', $fallback->sourceType);
        self::assertSame('ref-1', $fallback->sourceReference);

        $beneficiaryResolver = new CommissionBeneficiaryResolver();
        $beneficiary = $beneficiaryResolver->resolve(
            new CommissionRuleContextValueObject([
                'beneficiary_type' => 'affiliate',
                'beneficiary_reference' => 'beneficiary-1',
            ]),
        );
        $defaultBeneficiary = $beneficiaryResolver->resolve(new CommissionRuleContextValueObject([]));

        self::assertSame('affiliate', $beneficiary->beneficiaryType);
        self::assertSame('beneficiary-1', $beneficiary->beneficiaryReference);
        self::assertSame('partner', $defaultBeneficiary->beneficiaryType);
        self::assertSame('unresolved', $defaultBeneficiary->beneficiaryReference);
    }

    public function testRateResolverUsesRepositoryRatesAndFallbackTieredConfiguration(): void
    {
        $plan = new CommissionPlanEntity('plan-1', 'Plan 1');
        $rate = new CommissionRateEntity($plan, CommissionRateTypeEnum::Tiered, null, null, null);
        $tier = new CommissionTierEntity($rate, 0, 99_999, '5');

        $rateRepository = $this->createMock(CommissionRateRepositoryInterface::class);
        $rateRepository->expects(self::exactly(2))->method('findActiveByPlanCode')
            ->willReturnOnConsecutiveCalls([$rate], []);

        $tierRepository = $this->createMock(CommissionTierRepositoryInterface::class);
        $tierRepository->expects(self::once())->method('findByRate')->with($rate)->willReturn([$tier]);

        $resolver = new CommissionRateResolver($rateRepository, $tierRepository);

        $repositoryRate = $resolver->resolve(new CommissionRateResolutionRequestDTO('plan-1', 'USD', 50_000));
        $fallback = $resolver->resolve(new CommissionRateResolutionRequestDTO(
            'plan-1',
            'USD',
            150_000,
            [
                'commission_rate_type' => 'tiered',
                'commission_percentage_rate' => '9',
                'commission_fixed_minor_amount' => 300,
            ],
        ));

        self::assertSame('tiered', $repositoryRate->rateType);
        self::assertSame('USD', $repositoryRate->currencyCode);
        self::assertCount(1, $repositoryRate->tiers);
        self::assertSame('tiered', $fallback->rateType);
        self::assertSame('9', $fallback->percentageRate);
        self::assertSame(300, $fallback->fixedMinorAmount);
        self::assertCount(2, $fallback->tiers);
    }
}
