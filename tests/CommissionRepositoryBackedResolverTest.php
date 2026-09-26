<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use App\Commissioning\DTO\CommissionPlanResolutionRequestDTO;
use App\Commissioning\DTO\CommissionRateResolutionRequestDTO;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionTierEntity;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use App\Commissioning\Resolver\CommissionPlanResolver;
use App\Commissioning\Resolver\CommissionRateResolver;
use PHPUnit\Framework\TestCase;

final class CommissionRepositoryBackedResolverTest extends TestCase
{
    public function testPlanResolverUsesActiveRepositoryPlan(): void
    {
        $plan = new CommissionPlanEntity('enterprise', 'Enterprise plan');
        $repository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $repository->expects(self::once())->method('findOneByCode')->with('enterprise')->willReturn($plan);

        $result = (new CommissionPlanResolver($repository))
            ->resolve(new CommissionPlanResolutionRequestDTO('enterprise'));

        self::assertSame('enterprise', $result->planCode);
        self::assertSame('Enterprise plan', $result->planName);
    }

    public function testPlanResolverUsesExplicitContextFallbackWhenRepositoryHasNoActivePlan(): void
    {
        $repository = $this->createMock(CommissionPlanRepositoryInterface::class);
        $repository->expects(self::once())->method('findOneByCode')->with('partner')->willReturn(null);

        $result = (new CommissionPlanResolver($repository))
            ->resolve(new CommissionPlanResolutionRequestDTO(null, ['commission_plan_code' => 'partner']));

        self::assertSame('partner', $result->planCode);
        self::assertSame('Commission plan partner', $result->planName);
    }

    public function testRateResolverMapsRepositoryRateAndTiers(): void
    {
        $plan = new CommissionPlanEntity('enterprise', 'Enterprise plan');
        $rate = new CommissionRateEntity(
            $plan,
            CommissionRateTypeEnum::Tiered,
            null,
            null,
            'USD',
        );
        $firstTier = new CommissionTierEntity($rate, 0, 99_999, '5');
        $secondTier = new CommissionTierEntity($rate, 100_000, null, '8');

        $rateRepository = $this->createMock(CommissionRateRepositoryInterface::class);
        $rateRepository
            ->expects(self::once())
            ->method('findActiveByPlanCode')
            ->with('enterprise', 'USD')
            ->willReturn([$rate]);

        $tierRepository = $this->createMock(CommissionTierRepositoryInterface::class);
        $tierRepository
            ->expects(self::once())
            ->method('findByRate')
            ->with($rate)
            ->willReturn([$firstTier, $secondTier]);

        $result = (new CommissionRateResolver($rateRepository, $tierRepository))
            ->resolve(new CommissionRateResolutionRequestDTO('enterprise', 'USD', 120_000));

        self::assertSame('tiered', $result->rateType);
        self::assertSame('USD', $result->currencyCode);
        self::assertNull($result->percentageRate);
        self::assertNull($result->fixedMinorAmount);
        self::assertSame([
            [
                'minimumMinorAmount' => 0,
                'maximumMinorAmount' => 99_999,
                'percentageRate' => '5',
            ],
            [
                'minimumMinorAmount' => 100_000,
                'maximumMinorAmount' => null,
                'percentageRate' => '8',
            ],
        ], $result->tiers);
    }

    public function testRateResolverUsesExplicitDevelopmentFallbackWhenRepositoryHasNoRate(): void
    {
        $rateRepository = $this->createMock(CommissionRateRepositoryInterface::class);
        $rateRepository
            ->expects(self::once())
            ->method('findActiveByPlanCode')
            ->with('fallback-plan', 'USD')
            ->willReturn([]);

        $tierRepository = $this->createMock(CommissionTierRepositoryInterface::class);
        $tierRepository->expects(self::never())->method('findByRate');

        $result = (new CommissionRateResolver($rateRepository, $tierRepository))
            ->resolve(new CommissionRateResolutionRequestDTO(
                'fallback-plan',
                'USD',
                100_000,
                [
                    'commission_rate_type' => 'fixed',
                    'commission_percentage_rate' => '0',
                    'commission_fixed_minor_amount' => 750,
                ],
            ));

        self::assertSame('fixed', $result->rateType);
        self::assertSame('0', $result->percentageRate);
        self::assertSame(750, $result->fixedMinorAmount);
        self::assertSame('USD', $result->currencyCode);
        self::assertSame([], $result->tiers);
    }
}
