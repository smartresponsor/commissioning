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
        $createdPlans = 0;
        $createdRates = 0;
        $createdRules = 0;
        $createdTiers = 0;

        $plan = $this->planRepository->findOneByCode('default');

        if (!$plan instanceof CommissionPlanEntity) {
            $plan = new CommissionPlanEntity('default', 'Default Commission Plan');
            $this->planRepository->save($plan);
            ++$createdPlans;
        }

        $activeRates = $this->rateRepository->findActiveByPlanCode('default', 'USD');

        if ([] === $activeRates) {
            $percentageRate = new CommissionRateEntity(
                plan: $plan,
                type: CommissionRateTypeEnum::Percentage,
                percentageRate: '10',
                fixedMinorAmount: null,
                currencyCode: 'USD',
            );
            $this->rateRepository->save($percentageRate);
            ++$createdRates;

            $tieredRate = new CommissionRateEntity(
                plan: $plan,
                type: CommissionRateTypeEnum::Tiered,
                percentageRate: null,
                fixedMinorAmount: null,
                currencyCode: 'USD',
            );
            $this->rateRepository->save($tieredRate);
            ++$createdRates;

            $this->tierRepository->save(new CommissionTierEntity($tieredRate, 0, 99999, '5'));
            $this->tierRepository->save(new CommissionTierEntity($tieredRate, 100000, null, '8'));
            $createdTiers += 2;
        }

        $rules = $this->ruleRepository->findActiveByPlanCodeOrdered('default');

        if ([] === $rules) {
            $this->ruleRepository->save(new CommissionRuleEntity(
                plan: $plan,
                ruleKey: 'commission_enabled',
                operator: CommissionRuleOperatorEnum::Equals,
                expectedValue: '1',
                priority: 10,
            ));
            ++$createdRules;
        }

        return [
            'createdPlans' => $createdPlans,
            'createdRates' => $createdRates,
            'createdRules' => $createdRules,
            'createdTiers' => $createdTiers,
        ];
    }
}
