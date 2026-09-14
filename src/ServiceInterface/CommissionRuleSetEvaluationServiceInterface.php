<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

interface CommissionRuleSetEvaluationServiceInterface
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function matchesPlanRules(string $planCode, array $context): bool;
}
