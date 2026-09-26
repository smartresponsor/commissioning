<?php

declare(strict_types=1);

namespace App\Commissioning\ValueObject;

/**
 * Represents immutable Commissioning value semantics through CommissionRuleContextValueObject at typed application boundaries.
 */
final readonly class CommissionRuleContextValueObject
{
    /**
     * @param array<string, scalar|null> $values
     */
    public function __construct(public array $values)
    {
    }

    /**
     * Performs the get operation defined by this typed Commissioning application contract.
     */
    public function get(string $key): bool|float|int|string|null
    {
        return $this->values[$key] ?? null;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
