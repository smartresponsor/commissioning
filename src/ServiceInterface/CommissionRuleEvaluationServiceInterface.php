<?php

declare(strict_types=1);

namespace App\Commissioning\ServiceInterface;

/**
 * Defines the public Commissioning behavior contract exposed by CommissionRuleEvaluationServiceInterface to typed application collaborators.
 */
interface CommissionRuleEvaluationServiceInterface
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function matches(string $operator, string $ruleKey, ?string $expectedValue, array $context): bool;
}
