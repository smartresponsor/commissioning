<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionRuleEntity;
use App\Commissioning\Entity\CommissionTierEntity;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use App\Commissioning\Enum\CommissionRuleOperatorEnum;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRateRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionRuleRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTierRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionDevelopmentSeedServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionDevelopmentSeedService across typed collaborators and boundaries.
 */
final class CommissionDevelopmentSeedService implements CommissionDevelopmentSeedServiceInterface
{
    public function __construct(
        private readonly CommissionPlanRepositoryInterface $planRepository,
        private readonly CommissionRateRepositoryInterface $rateRepository,
        private readonly CommissionRuleRepositoryInterface $ruleRepository,
        private readonly CommissionTierRepositoryInterface $tierRepository,
    ) {
    }

    /**
     * @return array<string, scalar>
     */
    public function seedDefault(): array
    {
        $plan = $this->planRepository->findOneByCode('default');
        $createdPlans = 0;

        if (!$plan instanceof CommissionPlanEntity) {
            $plan = $this->createDefaultPlan();
            $createdPlans = 1;
        }

        [$createdRates, $createdTiers] = $this->seedDefaultRates($plan);
        $createdRules = $this->seedDefaultRule($plan);

        return [
            'createdPlans' => $createdPlans,
            'createdRates' => $createdRates,
            'createdRules' => $createdRules,
            'createdTiers' => $createdTiers,
        ];
    }

    private function createDefaultPlan(): CommissionPlanEntity
    {
        $plan = new CommissionPlanEntity('default', 'Default Commission Plan');
        $this->planRepository->save($plan);

        return $plan;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedDefaultRates(CommissionPlanEntity $plan): array
    {
        if ([] !== $this->rateRepository->findActiveByPlanCode('default', 'USD')) {
            return [0, 0];
        }

        $percentageRate = new CommissionRateEntity(
            plan: $plan,
            type: CommissionRateTypeEnum::Percentage,
            percentageRate: '10',
            fixedMinorAmount: null,
            currencyCode: 'USD',
        );
        $this->rateRepository->save($percentageRate);

        $tieredRate = new CommissionRateEntity(
            plan: $plan,
            type: CommissionRateTypeEnum::Tiered,
            percentageRate: null,
            fixedMinorAmount: null,
            currencyCode: 'USD',
        );
        $this->rateRepository->save($tieredRate);

        $this->tierRepository->save(new CommissionTierEntity($tieredRate, 0, 99999, '5'));
        $this->tierRepository->save(new CommissionTierEntity($tieredRate, 100000, null, '8'));

        return [2, 2];
    }

    private function seedDefaultRule(CommissionPlanEntity $plan): int
    {
        if ([] !== $this->ruleRepository->findActiveByPlanCodeOrdered('default')) {
            return 0;
        }

        $this->ruleRepository->save(new CommissionRuleEntity(
            plan: $plan,
            ruleKey: 'commission_enabled',
            operator: CommissionRuleOperatorEnum::Equals,
            expectedValue: '1',
            priority: 10,
        ));

        return 1;
    }
}
