<?php

declare(strict_types=1);

namespace App\Commissioning\Policy;

/**
 * Canonical lifecycle transition policy for this component.
 *
 * It is intentionally independent from Doctrine so entities, services, forms,
 * and CLI importers can use the same transition guard.
 */
final class CommissionLifecyclePolicy
{
    /** @var array<string, list<string>> */
    private const ALLOWED = [
        'draft' => ['calculated', 'cancelled'],
        'calculated' => ['approved', 'rejected', 'cancelled'],
        'approved' => ['settlement_pending', 'cancelled'],
        'settlement_pending' => ['settled', 'failed'],
        'failed' => ['settlement_pending', 'cancelled'],
        'settled' => [],
        'rejected' => [],
        'cancelled' => [],
    ];

    /**
     * Reports whether the requested Commissioning state transition satisfies the canonical lifecycle policy.
     */
    public static function canTransition(string $from, string $to): bool
    {
        $from = strtolower(trim($from));
        $to = strtolower(trim($to));

        return $from === $to || in_array($to, self::ALLOWED[$from] ?? [], true);
    }

    /**
     * Asserts the Commissioning application invariant represented by this contract and fails explicitly otherwise.
     */
    public static function assertCanTransition(string $from, string $to): void
    {
        if (!self::canTransition($from, $to)) {
            throw new \DomainException(sprintf('Invalid lifecycle transition from "%s" to "%s".', $from, $to));
        }
    }

    /** @return list<string> */
    public static function knownStates(): array
    {
        return array_values(array_unique(array_merge(array_keys(self::ALLOWED), ...array_values(self::ALLOWED))));
    }
}
