<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\RepositoryInterface\CommissionRuleRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionRuleEvaluationServiceInterface;
use App\Commissioning\ServiceInterface\CommissionRuleSetEvaluationServiceInterface;

final class CommissionRuleSetEvaluationService implements CommissionRuleSetEvaluationServiceInterface
{
    public function __construct(
        private readonly CommissionRuleRepositoryInterface $ruleRepository,
        private readonly CommissionRuleEvaluationServiceInterface $ruleEvaluationService,
    ) {
    }

    /**
     * @param array<string, scalar|null> $context
     */
    public function matchesPlanRules(string $planCode, array $context): bool
    {
        $rules = $this->ruleRepository->findActiveByPlanCodeOrdered($planCode);

        foreach ($rules as $rule) {
            if (!$this->ruleEvaluationService->matches(
                operator: $rule->getOperator()->value,
                ruleKey: $rule->getRuleKey(),
                expectedValue: $rule->getExpectedValue(),
                context: $context,
            )) {
                return false;
            }
        }

        return true;
    }
}
