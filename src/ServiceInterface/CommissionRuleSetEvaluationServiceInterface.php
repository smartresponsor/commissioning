<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionRuleSetEvaluationServiceInterface to typed application collaborators.
 */
interface CommissionRuleSetEvaluationServiceInterface
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function matchesPlanRules(string $planCode, array $context): bool;
}
