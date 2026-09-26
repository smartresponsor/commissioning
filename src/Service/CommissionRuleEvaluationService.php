<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\Enum\CommissionRuleOperatorEnum;
use App\Commissioning\ServiceInterface\CommissionRuleEvaluationServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionRuleEvaluationService across typed collaborators and boundaries.
 */
final class CommissionRuleEvaluationService implements CommissionRuleEvaluationServiceInterface
{
    /**
     * @param array<string, scalar|null> $context
     */
    public function matches(string $operator, string $ruleKey, ?string $expectedValue, array $context): bool
    {
        $actual = $context[$ruleKey] ?? null;

        return match (CommissionRuleOperatorEnum::from($operator)) {
            CommissionRuleOperatorEnum::Always => true,
            CommissionRuleOperatorEnum::Equals => (string) $actual === (string) $expectedValue,
            CommissionRuleOperatorEnum::NotEquals => (string) $actual !== (string) $expectedValue,
            CommissionRuleOperatorEnum::GreaterThanOrEqual => is_numeric($actual) && is_numeric($expectedValue) && $actual >= $expectedValue,
            CommissionRuleOperatorEnum::LessThanOrEqual => is_numeric($actual) && is_numeric($expectedValue) && $actual <= $expectedValue,
            CommissionRuleOperatorEnum::In => null !== $expectedValue && in_array((string) $actual, array_map('trim', explode(',', $expectedValue)), true),
        };
    }
}
